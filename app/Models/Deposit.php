<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'deposit_type',
        'refrence',
        'wallet',
        'payment_proof',
        'has_payment_proof',
        'status',
        'amount',
        'has_cookie',
        'stored_cookie',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
