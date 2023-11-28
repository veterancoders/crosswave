<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Payoutrequest extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'amount',
        'user_id',
        'investment_id',
        'status',
        'currency'

    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
