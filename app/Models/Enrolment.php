<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrolment extends Model
{
    protected $table = 'enrolments';

    protected $primaryKey = 'enrolment_id';

    protected $fillable = [
        'student_id',
        'season_id',
        'enrolment_code',
        'study_area',
        'enrolment_date',
        'start_date',
        'end_date',
        'due_date',
        'total_payment',
        'debt_status',
    ];

    /**
     * Relationship with the Student model.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Relationship with the Season model.
     */
    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id', 'season_id');
    }
}