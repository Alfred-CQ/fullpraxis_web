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
        Schema::create('enrolments', function (Blueprint $table) {
            $table->id('enrolment_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('season_id');
            $table->string('enrolment_code', 8);
            $table->string('study_area');
            $table->date('enrolment_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('due_date');
            $table->decimal('total_payment', 10, 2);
            $table->string('debt_status');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('season_id')->references('season_id')->on('seasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrolments');
    }
};
