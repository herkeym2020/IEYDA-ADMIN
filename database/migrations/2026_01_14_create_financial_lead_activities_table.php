<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('financial_lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('financial_member_leads')->onDelete('cascade');
            $table->string('type'); // email_sent|email_opened|note_added|status_changed|tag_added|contacted
            $table->text('description')->nullable();
            $table->json('data')->nullable(); // Store additional context
            $table->timestamps();
            $table->index(['lead_id']);
            $table->index(['created_at']);
            $table->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_lead_activities');
    }
};
