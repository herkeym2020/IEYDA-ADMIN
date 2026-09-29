<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category');
            $table->string('image');
            $table->date('event_date');
            $table->string('event_time');
            $table->string('location');
            $table->string('organizer')->nullable();
            $table->string('phone')->nullable();
            $table->string('attendees')->default('100+');
            $table->string('registration_fee')->default('Free');
            $table->date('registration_deadline')->nullable();
            $table->string('contact_email');
            $table->string('registration_link', 500)->nullable();
            $table->json('highlights')->nullable();
            $table->json('speakers')->nullable();
            $table->json('outcomes')->nullable();
            $table->enum('status', ['upcoming', 'completed', 'cancelled'])->default('upcoming');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
