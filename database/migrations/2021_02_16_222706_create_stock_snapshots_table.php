<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_snapshots', function (Blueprint $table) {
            $table->id();

            $table->timestamp('time');

            $table->unsignedBigInteger('stock_id');
            $table->integer('popularity');

            $table->index(['stock_id']);
            $table->index(['popularity']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_snapshots');
    }
}
