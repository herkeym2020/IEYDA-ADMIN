<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quran_competition_participants', function (Blueprint $table) {
            // Change category from enum to string to store JSON array of selected categories
            $table->string('category', 500)->change();
        });
    }

    public function down(): void
    {
        Schema::table('quran_competition_participants', function (Blueprint $table) {
            $table->enum('category', ['markaz', 'adaby', 'zumurah', 'imam-agba', 'asily'])->change();
        });
    }
};