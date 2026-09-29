<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name')->nullable();
            $table->string('donor_email');
            $table->string('donor_phone')->nullable();
            $table->integer('amount_ngn');
            $table->string('currency', 8)->default('NGN');
            $table->string('status', 24)->default('pending'); // pending|processing|success|failed
            $table->string('provider', 32)->default('paystack');
            $table->string('provider_reference')->nullable()->unique();
            $table->string('provider_access_code')->nullable();
            $table->string('authorization_url')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['donor_email']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
