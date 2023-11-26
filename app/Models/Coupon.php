<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'currency_code',
        'description',
        'discount',
        'min_amount',
        'max_coupon_amount',
        'percentage',
        'expires_on',
        'times',
        'is_active',

    ];
}
