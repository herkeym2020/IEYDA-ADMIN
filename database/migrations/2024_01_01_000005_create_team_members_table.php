<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('staff'); // grand_patron, board_of_trustees, executive_present, executive_pioneering, staff, volunteer
            $table->string('salute')->nullable(); // For grand patron
            $table->string('name');
            $table->string('awards')->nullable(); // For grand patron
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('image');
            $table->text('bio')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('social_links')->nullable();
            $table->string('executive_type')->nullable(); // present, pioneering (for executives)
            $table->string('term')->nullable(); // for executives if needed
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
