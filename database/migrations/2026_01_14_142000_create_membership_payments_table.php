<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('email')->index();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending|completed|failed
            $table->string('form_access_token')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->json('transaction_data')->nullable();
            $table->timestamps();
            
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_payments');
    }
};
