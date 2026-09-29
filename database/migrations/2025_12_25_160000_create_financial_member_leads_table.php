<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('financial_member_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('source_form_id')->nullable();
            $table->timestamp('form_submitted_at')->nullable();
            $table->json('data')->nullable();
            $table->string('status', 24)->default('new'); // new|contacted|qualified|converted
            $table->string('tag')->nullable(); // bronze|silver|gold|premium
            $table->text('notes')->nullable();
            $table->integer('interaction_count')->default(0);
            $table->timestamp('ack_sent_at')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('last_interaction_at')->nullable();
            $table->timestamps();
            $table->index(['created_at']);
            $table->index(['status']);
            $table->index(['tag']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_member_leads');
    }
};
