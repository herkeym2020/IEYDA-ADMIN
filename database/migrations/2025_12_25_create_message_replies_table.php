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
        Schema::create('message_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_message_id')->constrained('contact_messages')->onDelete('cascade');
            $table->text('reply_text');
            $table->enum('reply_from', ['admin', 'auto'])->default('admin'); // Track if auto or manual reply
            $table->string('reply_template')->nullable(); // Template name if auto-generated
            $table->boolean('sent_to_user')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Indexes for faster queries
            $table->index('contact_message_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_replies');
    }
};
