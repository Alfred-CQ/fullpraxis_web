<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Person extends Model
{
    use HasFactory;

    protected $table = 'people';
    protected $primaryKey = 'id';
    protected $fillable = [
        'doi',
        'first_names',
        'last_names',
        'phone_number',
        'person_type',
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'person_id');
    }
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'person_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'person_id', 'id');
    }
    public function enrollment() 
    {
        return $this->hasMany(Enrollment::class, 'person_id');
    }
    /*
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where(fn($query) =>
                $query->where('doi', 'like', '%' . $search . '%')
                    ->orWhere('first_names', 'like', '%' . $search . '%')
                    ->orWhere('last_names', 'like', '%' . $search . '%')
            )
        );
    }

    */
}
