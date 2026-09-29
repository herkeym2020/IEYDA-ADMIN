<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lga');
            $table->text('description')->nullable();
            $table->enum('status', ['approved', 'pending', 'declined'])->default('pending');
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('position')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('communities');
    }
};
