<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meeting_notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('meeting_type')->nullable();
            $table->text('summary');
            $table->longText('details')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->string('location')->nullable();
            $table->string('action_label')->nullable();
            $table->string('action_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('show_popup')->default(true);
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();
            $table->index(['is_active', 'show_popup', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_notices');
    }
};
