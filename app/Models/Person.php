<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    protected $primaryKey = 'person_id';

    protected $fillable = [
        'doi',
        'first_names',
        'last_names',
        'mobile_number',
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'person_id', 'person_id');
    }
}
