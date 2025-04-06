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
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->unsignedBigInteger('person_id');
            $table->date('birth_date');
            $table->string('guardian_mobile_number', 9);
            $table->string('graduated_high_school', 100);
            $table->string('photo_path')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('person_id')
                  ->references('person_id')
                  ->on('persons')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
