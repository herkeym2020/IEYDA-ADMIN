<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category');
            $table->string('image');
            $table->string('icon')->nullable();
            $table->string('beneficiaries')->nullable();
            $table->string('budget')->nullable();
            $table->string('duration')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('objectives')->nullable();
            $table->json('achievements')->nullable();
            $table->json('partners')->nullable();
            $table->json('locations')->nullable();
            $table->integer('progress')->nullable();
            $table->string('coordinator')->nullable();
            $table->enum('status', ['active', 'upcoming', 'inactive'])->default('active');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
