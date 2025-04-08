<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('doi', 8)->unique(); // identification number
            $table->string('first_names', 50);
            $table->string('last_names', 50);
            $table->string('phone_number', 9)->nullable();
            $table->enum('person_type', ['Student', 'Teacher', 'Other'])->nullable();
            //$table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
