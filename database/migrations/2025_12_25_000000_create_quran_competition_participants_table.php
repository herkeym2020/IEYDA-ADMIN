<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quran_competition_participants', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            // Personal Information
            $table->string('full_name');
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->integer('age');
            $table->string('school')->nullable();
            $table->string('lga');
            $table->string('state');
            $table->text('address');
            // Parent / Guardian
            $table->string('guardian_name');
            $table->string('relationship');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('emergency_name');
            $table->string('emergency_phone');
            // Islamic Education
            $table->string('madrasah');
            $table->string('teacher_name');
            $table->string('teacher_phone')->nullable();
            // Competition
            $table->enum('category', ['markaz', 'adaby', 'zumurah', 'imam-agba', 'asily']);
            $table->string('photo_path')->nullable();
            // Declaration
            $table->boolean('decl_age')->default(false);
            $table->boolean('decl_accurate')->default(false);
            $table->boolean('decl_consent')->default(false);
            $table->boolean('decl_rules')->default(false);
            // Status & meta
            $table->enum('status', ['pending', 'under_review', 'verified', 'approved', 'shortlisted', 'finalist', 'winner', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quran_competition_participants');
    }
};