<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['symbol'];

    protected $casts = [
        'first_trade_date' => 'datetime',
        'earnings' => 'datetime',
    ];

    private $popularityGraphCache;

    public function snapshots()
    {
        return $this->hasMany(StockSnapshot::class);
    }

    public function snapshot()
    {
        return $this->hasOne(StockSnapshot::class)->orderBy('id', 'desc');
    }

    /**
     * The result of this function is cached as it is used repeatedly
     * and laravel won't cache accessor functions by default.
     */
    public function getPopularityGraphAttribute($force = false)
    {
        if (!$this->popularityGraphCache || $force) {
            $this->load(['snapshots' => function ($query) {
                $query->where('time', '>', now()->subDays(7));
            }]);

            $now = now();
            $snapshots = $this->snapshots->groupBy(function ($item) use ($now) {
                return $now->diffInHours($item->time);
            });

            $points = [];
            $x1 = 0;
            $y1 = 0;
            for ($hour = 0; $hour < 168; $hour++) {
                if ($snapshots->has(168 - $hour)) {
                    $points[] = $snapshots[168 - $hour]->avg('popularity');
                } else {
                    $noise = rand(-5, 5);
                    if ($hour == 0) {
                        $points[] = $noise;
                    } else {
                        // linear interpolation.
                        if ($hour >= $x1) {
                            for ($x1 = $hour; $x1 < 168; $x1++) {
                                if ($snapshots[168 - $x1] ?? false) {
                                    $y1 = $snapshots[168 - $x1]->avg('popularity');
                                    break;
                                }
                            }
                        }
                        $d = 1 / ($x1 - $hour);
                        $y = $points[$hour - 1] * (1 - $d) + $y1 * $d;
                        $points[] = $y + $noise;
                    }
                }
            }

            return $points;


            // $this->popularityGraphCache = [];
            // foreach (now()->subDays(7)->hoursUntil(now()) as $date) {
            //     if ($snap)
            // }
        }
        return $this->popularityGraphCache;
    }

    public function getPopularityGraphLowAttribute()
    {
        return min($this->popularityGraph) + $this->popularitySpread();
    }

    public function getPopularityGraphHighAttribute()
    {
        return max($this->popularityGraph) + $this->popularitySpread();
    }

    private function popularitySpread()
    {
        return (max($this->popularityGraph) - min($this->popularityGraph)) / 2;
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class)
            ->orderBy('popularity', 'desc');
    }

    public function usersHolding()
    {
        return $this->belongsToMany(User::class, 'positions')->using(Position::class);
    }

    public function usersWatching()
    {
        return $this->belongsToMany(User::class, 'watchlist')->using(Watcher::class);
    }

    public function getMarkedAttribute()
    {
        return Auth::user()->stocksInWatchlist->contains($this)
            || Auth::user()->stocksInPositions->contains($this);
    }

    public function getColorAttribute()
    {
        return substr(md5($this->symbol), 0, 6);
    }

    public function getUpAttribute()
    {
        return $this->regular_market_change_percent >= 0;
    }

    public function getMarketCapForHumansAttribute()
    {
        if ($this->market_cap < 1000000) {
            // Anything less than a million
            return number_format($this->market_cap);
        } elseif ($this->market_cap < 1000000000) {
            // Anything less than a billion
            return number_format($this->market_cap / 1000000, 2) . 'M';
        } else {
            // At least a billion
            return number_format($this->market_cap / 1000000000, 2) . 'B';
        }
    }

    public static function trending($type = 'all')
    {
        $builder = StockSnapshot::with('stock');

        if ($type == 'positions') {
            $builder->whereIn('stock_id', Auth::user()->stocksInPositions->pluck('id'));
        } elseif ($type == 'watchlist') {
            $builder->whereIn('stock_id', Auth::user()->stocksInWatchlist->pluck('id'));
        }

        return $builder
            ->groupBy('stock_id')
            ->selectRaw('stock_id, MAX(id)')
            ->orderBy('popularity', 'desc')
            ->limit(5)
            ->get()
            ->pluck('stock');
    }

    public static function updateTrending()
    {
        $trending = self::trending();
        foreach ($trending as $stock) {
            Post::updateList($stock->posts);
        }
    }

    /**
     * This function has side-effects. It will save the stock to the db if
     * found.
     */
    public static function fromYahoo($symbol)
    {
        // Ignore symbol if it's already in the failed lookup table.
        if (DB::table('failed_symbol_lookups')->where('symbol', '=', $symbol)->count() != 0) {
            return null;
        }

        $stock = self::where('symbol', $symbol)->first();

        // We might not want to update a stock we already have.
        if ($stock) {
            // Do not run this update if the data is newer than 15 minutes.
            if ($stock->updated_at->diffInMinutes(now()) <= 15) {
                return $stock;
            }

            // Do not update if the last update was outside of market hours.
            $ny = $stock->updated_at->setTimezone('America/New_York');
            if ($ny->hour >= 16 || ($ny->hour < 6 && $ny->minute < 30) || $ny->dayOfWeek >= 6) {
                return $stock;
            }
        }

        // Try to http query yahoo finance data.
        $results = Http::get('https://query1.finance.yahoo.com/v7/finance/options/' . $symbol)
            ->json('optionChain.result');
        $inc = $results[0]['quote'] ?? false;

        // Yahoo data not available. Should probably stop looking.
        if (!$inc) {
            DB::table('failed_symbol_lookups')->insert([
                'symbol' => $symbol,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            Log::info('Yahoo Symbol Lookup FAILED for: ' . $symbol);
            return null;
        }

        // Incoming data is not formatted how we expect. No update possible.
        if (!isset($inc['longName'])) {
            return $stock;
        }

        if (!$stock) {
            $stock = new Stock();
            $stock->symbol = $symbol;
        }

        $stock->name = $inc['shortName'] ?? $inc['longName'];
        $stock->exchange = $inc['exchange'];

        $stock->fifty_two_week_low_change = $inc['fiftyTwoWeekLowChange'] ?? null;
        $stock->fifty_two_week_low_change_percent = $inc['fiftyTwoWeekLowChangePercent'] ?? null;
        $stock->fifty_two_week_range = $inc['fiftyTwoWeekRange'] ?? null;
        $stock->fifty_two_week_high_change = $inc['fiftyTwoWeekHighChange'] ?? null;
        $stock->fifty_two_week_high_change_percent = $inc['fiftyTwoWeekHighChangePercent'] ?? null;
        $stock->fifty_two_week_low = $inc['fiftyTwoWeekLow'] ?? null;
        $stock->fifty_two_week_high = $inc['fiftyTwoWeekHigh'] ?? null;
        $stock->eps_trailing_twelve_months = $inc['epsTrailingTwelveMonths'] ?? null;
        $stock->shares_outstanding = $inc['sharesOutstanding'] ?? null;
        $stock->book_value = $inc['bookValue'] ?? null;
        $stock->fifty_day_average = $inc['fiftyDayAverage'] ?? null;
        $stock->fifty_day_average_change = $inc['fiftyDayAverageChange'] ?? null;
        $stock->fifty_day_average_change_percent = $inc['fiftyDayAverageChangePercent'] ?? null;
        $stock->two_hundred_day_average = $inc['twoHundredDayAverage'] ?? null;
        $stock->two_hundred_day_average_change = $inc['twoHundredDayAverageChange'] ?? null;
        $stock->two_hundred_day_average_change_percent = $inc['twoHundredDayAverageChangePercent'] ?? null;
        $stock->market_cap = $inc['marketCap'] ?? null;
        $stock->price_to_book = $inc['priceToBook'] ?? null;
        $stock->source_interval = $inc['sourceInterval'] ?? null;
        $stock->exchange_data_delayed_by = $inc['exchangeDataDelayedBy'] ?? null;
        $stock->regular_market_change = $inc['regularMarketChange'] ?? null;
        $stock->regular_market_change_percent = $inc['regularMarketChangePercent'] ?? null;
        $stock->regular_market_time = $inc['regularMarketTime'] ?? null;
        $stock->regular_market_price = $inc['regularMarketPrice'] ?? null;
        $stock->regular_market_day_high = $inc['regularMarketDayHigh'] ?? null;
        $stock->regular_market_day_range = $inc['regularMarketDayRange'] ?? null;
        $stock->regular_market_day_low = $inc['regularMarketDayLow'] ?? null;
        $stock->regular_market_volume = $inc['regularMarketVolume'] ?? null;
        $stock->regular_market_previous_close = $inc['regularMarketPreviousClose'] ?? null;
        $stock->regular_market_open = $inc['regularMarketOpen'] ?? null;
        $stock->average_daily_volume_3_month = $inc['averageDailyVolume3Month'] ?? null;
        $stock->average_daily_volume_10_day = $inc['averageDailyVolume10Day'] ?? null;

        $stock->first_trade_date = isset($inc['firstTradeDateMilliseconds'])
            ? $inc['firstTradeDateMilliseconds'] / 1000
            : null;
        $stock->earnings = $inc['earningsTimestamp'] ?? null;

        $stock->updated_at = now();
        $stock->save();

        return $stock;
    }
}
