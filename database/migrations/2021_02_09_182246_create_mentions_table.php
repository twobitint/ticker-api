<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mentions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('url')->unique();
            $table->string('type');
            $table->string('title', 512)->nullable();
            $table->text('content')->nullable();
            $table->string('source');
            $table->string('author')->nullable();
            $table->string('category')->nullable();

            $table->string('subreddit')->nullable();

            $table->integer('comment_count')->nullable();
            $table->datetime('posted_at');

            $table->integer('score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mentions');
    }
}
