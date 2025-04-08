<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{

    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'name',
        'monthly_discount',
        'enrollment_discount',
        'description',
    ];

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

}
