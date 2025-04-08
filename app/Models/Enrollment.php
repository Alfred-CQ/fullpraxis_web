<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';

    protected $fillable = [
        'person_id',
        'academic_term_id',
        'enrollment_date',
        'start_date',
        'end_date',
        'due_date',
        'total_payment',
        'debt_status',
        'study_area',
    ];


    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'person_id');
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class, 'academic_term_id', 'academic_term_id');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'enrollment_id', 'enrollment_id');
    }
}