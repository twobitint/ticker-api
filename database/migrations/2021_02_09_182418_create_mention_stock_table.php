<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMentionStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mention_stock', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('mention_id');
            $table->unsignedBigInteger('stock_id');

            $table->unique(['mention_id', 'stock_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mention_stock');
    }
}
