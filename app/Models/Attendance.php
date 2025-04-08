<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'person_id',
        'recorded_at',
        'attendance_type',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'person_id');
    }

}
