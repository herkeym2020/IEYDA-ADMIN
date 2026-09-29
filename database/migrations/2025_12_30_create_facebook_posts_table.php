<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_posts', function (Blueprint $table) {
            $table->id();
            $table->string('facebook_post_id')->unique()->index();
            $table->string('facebook_page_id')->index();
            $table->text('message')->nullable();
            $table->text('story')->nullable();
            $table->string('full_picture')->nullable();
            $table->string('link')->nullable();
            $table->string('post_url')->nullable();
            $table->dateTime('created_time')->nullable();
            $table->dateTime('imported_at')->nullable();
            $table->unsignedBigInteger('news_id')->nullable();
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->json('raw_data')->nullable()->comment('Full Facebook post data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_posts');
    }
};
