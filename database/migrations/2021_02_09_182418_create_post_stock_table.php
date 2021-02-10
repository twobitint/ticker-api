<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_stock', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('stock_id');

            $table->unique(['post_id', 'stock_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_stock');
    }
}
