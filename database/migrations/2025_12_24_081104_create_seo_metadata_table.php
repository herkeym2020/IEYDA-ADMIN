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
        Schema::create('seo_metadata', function (Blueprint $table) {
            $table->id();
            $table->string('seoable_type'); // Model type
            $table->unsignedBigInteger('seoable_id'); // Model ID
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('slug')->unique();
            $table->string('og_image')->nullable(); // Open Graph image
            $table->text('og_description')->nullable(); // Open Graph description
            $table->string('canonical_url')->nullable();
            $table->json('schema_markup')->nullable(); // Structured data
            $table->text('keywords')->nullable(); // CSV of keywords
            $table->integer('keyword_density')->nullable(); // Score 0-100
            $table->enum('seo_score', ['poor', 'good', 'excellent'])->default('good');
            $table->integer('word_count')->default(0);
            $table->boolean('has_meta_title')->default(false);
            $table->boolean('has_meta_description')->default(false);
            $table->timestamps();
            
            $table->unique(['seoable_type', 'seoable_id']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_metadata');
    }
};
