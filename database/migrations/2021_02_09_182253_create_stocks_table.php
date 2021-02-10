<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('symbol');
            $table->string('name');
            $table->string('exchange');

            $table->string('fifty_two_week_low_change')->nullable();
            $table->string('fifty_two_week_low_change_percent')->nullable();
            $table->string('fifty_two_week_range')->nullable();
            $table->string('fifty_two_week_high_change')->nullable();
            $table->string('fifty_two_week_high_change_percent')->nullable();
            $table->string('fifty_two_week_low')->nullable();
            $table->string('fifty_two_week_high')->nullable();
            $table->string('eps_trailing_twelve_months')->nullable();
            $table->string('shares_outstanding')->nullable();
            $table->string('book_value')->nullable();
            $table->string('fifty_day_average')->nullable();
            $table->string('fifty_day_average_change')->nullable();
            $table->string('fifty_day_average_change_percent')->nullable();
            $table->string('two_hundred_day_average')->nullable();
            $table->string('two_hundred_day_average_change')->nullable();
            $table->string('two_hundred_day_average_change_percent')->nullable();
            $table->string('market_cap')->nullable();
            $table->string('price_to_book')->nullable();
            $table->string('source_interval')->nullable();
            $table->string('exchange_data_delayed_by')->nullable();
            $table->string('regular_market_change')->nullable();
            $table->string('regular_market_change_percent')->nullable();
            $table->string('regular_market_time')->nullable();
            $table->string('regular_market_price')->nullable();
            $table->string('regular_market_day_high')->nullable();
            $table->string('regular_market_day_range')->nullable();
            $table->string('regular_market_day_low')->nullable();
            $table->string('regular_market_volume')->nullable();
            $table->string('regular_market_previous_close')->nullable();
            $table->string('regular_market_open')->nullable();
            $table->string('average_daily_volume_3_month')->nullable();
            $table->string('average_daily_volume_10_day')->nullable();

            $table->datetime('first_trade_date')->nullable();
            $table->datetime('earnings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
