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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->foreignId('academic_term_id')->constrained('academic_terms')->onDelete('cascade');

            $table->date('enrollment_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('due_date');

            $table->decimal('total_payment', 10, 2);
            $table->enum('debt_status', ['Paid', 'Pending', 'Overdue']);
            $table->string('study_area', 15);

            // $table->softDeletes();
            $table->timestamps();

            $table->unique(['person_id', 'academic_term_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropForeign(['academic_term_id']);
        });
        Schema::dropIfExists('enrollments');
    }
};
