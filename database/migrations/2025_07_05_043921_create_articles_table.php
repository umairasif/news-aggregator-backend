<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
          $table->id();
          $table->string('title', 512);
          $table->string('title_slug', 512)->unique();
          $table->text('description')->nullable();
          $table->string('url', 2084);
          $table->string('image_url', 2084)->nullable();
          $table->string('source', 100)->index();
          $table->string('category', 100)->index();
          $table->string('author', 150)->nullable();
          $table->longText('content')->nullable();
          $table->dateTime('published_at');;

          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
