<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'id';

    protected $fillable = [
        'person_id',
        'birth_date',
        'guardian_phone',
        'high_school_name',
        'photo_path',
        'carnet_path',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }
}
