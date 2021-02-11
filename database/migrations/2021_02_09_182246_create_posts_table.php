<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title', 512);
            $table->text('content')->nullable();

            $table->string('url');
            $table->string('source');
            $table->string('author');
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->datetime('posted_at');

            $table->integer('score')->nullable();
            $table->decimal('score_confidence', 3, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
