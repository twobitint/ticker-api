<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Stock extends Model
{
    use HasFactory;

    protected $casts = [
        'first_trade_date' => 'datetime',
        'earnings' => 'datetime',
    ];

    /**
     * This function has side-effects. It will save the stock to the db if
     * found.
     */
    public function updateFromYahoo()
    {
        if (!$this->symbol) {
            return;
        }

        // Do not run this update if the data is newer than 15 minutes.
        if ($this->updated_at && $this->updated_at->addMinutes(15)->lt(now())) {
            return;
        }

        // Try to http query yahoo finance data.
        $results = Http::get('https://query1.finance.yahoo.com/v7/finance/options/' . $this->symbol)
            ->json('optionChain.result');
        $inc = $results[0]['quote'] ?? false;

        // Incoming data is not formatted how we expect. No update possible.
        if (!$inc) {
            return;
        }

        $this->name = $inc['longName'];
        $this->exchange = $inc['exchange'];

        $this->fifty_two_week_low_change = $inc['fiftyTwoWeekLowChange'] ?? null;
        $this->fifty_two_week_low_change_percent = $inc['fiftyTwoWeekLowChangePercent'] ?? null;
        $this->fifty_two_week_range = $inc['fiftyTwoWeekRange'] ?? null;
        $this->fifty_two_week_high_change = $inc['fiftyTwoWeekHighChange'] ?? null;
        $this->fifty_two_week_high_change_percent = $inc['fiftyTwoWeekHighChangePercent'] ?? null;
        $this->fifty_two_week_low = $inc['fiftyTwoWeekLow'] ?? null;
        $this->fifty_two_week_high = $inc['fiftyTwoWeekHigh'] ?? null;
        $this->eps_trailing_twelve_months = $inc['epsTrailingTwelveMonths'] ?? null;
        $this->shares_outstanding = $inc['sharesOutstanding'] ?? null;
        $this->book_value = $inc['bookValue'] ?? null;
        $this->fifty_day_average = $inc['fiftyDayAverage'] ?? null;
        $this->fifty_day_average_change = $inc['fiftyDayAverageChange'] ?? null;
        $this->fifty_day_average_change_percent = $inc['fiftyDayAverageChangePercent'] ?? null;
        $this->two_hundred_day_average = $inc['twoHundredDayAverage'] ?? null;
        $this->two_hundred_day_average_change = $inc['twoHundredDayAverageChange'] ?? null;
        $this->two_hundred_day_average_change_percent = $inc['twoHundredDayAverageChangePercent'] ?? null;
        $this->market_cap = $inc['marketCap'] ?? null;
        $this->price_to_book = $inc['priceToBook'] ?? null;
        $this->source_interval = $inc['sourceInterval'] ?? null;
        $this->exchange_data_delayed_by = $inc['exchangeDataDelayedBy'] ?? null;
        $this->regular_market_change = $inc['regularMarketChange'] ?? null;
        $this->regular_market_change_percent = $inc['regularMarketChangePercent'] ?? null;
        $this->regular_market_time = $inc['regularMarketTime'] ?? null;
        $this->regular_market_price = $inc['regularMarketPrice'] ?? null;
        $this->regular_market_day_high = $inc['regularMarketDayHigh'] ?? null;
        $this->regular_market_day_range = $inc['regularMarketDayRange'] ?? null;
        $this->regular_market_day_low = $inc['regularMarketDayLow'] ?? null;
        $this->regular_market_volume = $inc['regularMarketVolume'] ?? null;
        $this->regular_market_previous_close = $inc['regularMarketPreviousClose'] ?? null;
        $this->regular_market_open = $inc['regularMarketOpen'] ?? null;
        $this->average_daily_volume_3_month = $inc['averageDailyVolume3Month'] ?? null;
        $this->average_daily_volume_10_day = $inc['averageDailyVolume10Day'] ?? null;

        $this->first_trade_date = $inc['firstTradeDateMilliseconds'] ?? null;
        $this->earnings = $inc['earningsTimestamp'];

        $this->updated_at = now();
        $this->save();
    }
}
