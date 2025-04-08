<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory;

    protected $table = 'receipts';

    protected $fillable = [
        'enrollment_id',
        'discount_id',
        'receipt_date',
        'payment_date',
        'enrollment_payment',
        'monthly_payment',
        'notes'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'enrollment_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'discount_id');
    }
}
