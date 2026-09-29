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
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('revisionable_type'); // Model type (News, Event, etc.)
            $table->unsignedBigInteger('revisionable_id'); // Model ID
            $table->string('key'); // Field that was changed
            $table->longText('old_value')->nullable();
            $table->longText('new_value')->nullable();
            $table->string('action')->default('updated'); // created, updated, deleted
            $table->text('description')->nullable(); // Human-readable change
            $table->timestamps();
            
            $table->index(['revisionable_type', 'revisionable_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
