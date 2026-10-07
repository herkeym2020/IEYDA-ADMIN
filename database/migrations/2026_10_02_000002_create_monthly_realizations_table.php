<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monthly_realizations', function (Blueprint $table) {
            $table->id();
            $table->date('month');
            $table->string('title');
            $table->string('community_name');
            $table->string('lga')->nullable();
            $table->text('summary');
            $table->longText('details')->nullable();
            $table->string('impact_metric')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();
            $table->index(['is_active', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_realizations');
    }
};
