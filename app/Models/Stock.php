<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['symbol'];

    protected $casts = [
        'first_trade_date' => 'datetime',
        'earnings' => 'datetime',
    ];

    /**
     * This function has side-effects. It will save the stock to the db if
     * found.
     */
    public static function fromYahoo($symbol)
    {
        $stock = self::where('symbol', $symbol)->first();

        // Do not run this update if the data is newer than 15 minutes.
        if ($stock && $stock->updated_at && $stock->updated_at->diffInMinutes(now()) <= 15) {
            return $stock;
        }

        // Try to http query yahoo finance data.
        $results = Http::get('https://query1.finance.yahoo.com/v7/finance/options/' . $symbol)
            ->json('optionChain.result');
        $inc = $results[0]['quote'] ?? false;

        // Incoming data is not formatted how we expect. No update possible.
        if (!$inc || !isset($inc['longName'])) {
            return $stock;
        }

        if (!$stock) {
            $stock = new Stock();
            $stock->symbol = $symbol;
        }

        $stock->name = $inc['longName'];
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