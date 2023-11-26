<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    public $table = 'currencies';
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'country_code',
    ];

}
