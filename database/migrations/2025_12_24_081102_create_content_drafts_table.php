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
        Schema::create('content_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('content_type'); // news, events, programs, etc.
            $table->unsignedBigInteger('content_id')->nullable();
            $table->string('title');
            $table->longText('description');
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamp('scheduled_at')->nullable(); // When to publish
            $table->enum('status', ['draft', 'scheduled', 'auto-saved'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_drafts');
    }
};
