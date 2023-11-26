<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'signup_fee',
        'profit_percent',
        'invoice_period',
        'invoice_interval',
        'trial_period',
        'trial_interval',
        'sort_order',
        'currency',
        'slug',
    ];
}
