<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ict_programme_applicants', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('full_name');
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->integer('age');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('address');
            $table->string('lga', 100)->nullable();
            $table->string('state', 100)->default('Kwara');
            $table->string('education_level', 100)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->json('ict_courses')->nullable();
            $table->json('vocational_interests')->nullable();
            $table->text('expectations')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('guardian_name', 255)->nullable();
            $table->string('guardian_phone', 30)->nullable();
            $table->string('guardian_relationship', 100)->nullable();
            $table->boolean('decl_accurate')->default(false);
            $table->boolean('decl_consent')->default(false);
            $table->boolean('decl_rules')->default(false);
            $table->enum('status', ['pending', 'approved', 'admitted', 'rejected', 'completed', 'graduated'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('admitted_at')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamp('whatsapp_sent_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('registration_number');
            $table->index('phone');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ict_programme_applicants');
    }
};