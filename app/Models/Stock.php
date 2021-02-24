<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['symbol'];

    protected $casts = [
        'first_trade_date' => 'datetime',
        'earnings' => 'datetime',
    ];

    private $popularityGraphCache;

    /**
     * The result of this function is cached as it is used repeatedly
     * and laravel won't cache accessor functions by default.
     */
    public function getPopularityGraphAttribute($force = false)
    {
        if (!$this->popularityGraphCache || $force) {
            $bins = [];
            $totalBins = 7;
            for ($i = 0; $i < $totalBins; $i++) {
                $bins[$i] = 1;
            }
            foreach ($this->mentions as $mention) {
                $bin = (int)($mention->posted_at->diffInDays());
                if ($bin < $totalBins) {
                    $bins[$bin]++; //$mention->score;
                }
            }
            $this->popularityGraphCache = array_reverse($bins);
        }
        return $this->popularityGraphCache;
    }

    public function getPopularityGraphLowAttribute()
    {
        return $this->popularityGraph->min('y') - $this->popularitySpread();
    }

    public function getPopularityGraphHighAttribute()
    {
        return $this->popularityGraph->max('y') + $this->popularitySpread();
    }

    private function popularitySpread()
    {
        return ($this->popularityGraph->max('y') - $this->popularityGraph->min('y')) / 2;
    }

    public function posts()
    {
        return $this->mentions()
            ->where('type', 'post')
            ->orderBy('score', 'desc');
    }

    public function comments()
    {
        return $this->mentions()
            ->where('type', 'comment');
    }

    public function mentions()
    {
        return $this->belongsToMany(Mention::class)
            ->where('posted_at', '>', now()->subWeek());
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
        return Stock::whereHas('mentions', function ($query) {
            $query->where('posted_at', '>', now()->subHours(1));
        })->withCount('mentions')
            ->orderBy('mentions_count', 'desc')
            ->limit(10)
            ->get();
    }
}
