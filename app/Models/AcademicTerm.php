<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicTerm extends Model
{
    use HasFactory;

    protected $table = 'academic_terms';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'enrollment_cost',
        'monthly_cost',
        'status',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'academic_term_id');
    }
    
}

