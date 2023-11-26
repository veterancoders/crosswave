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


    public function investment()
    {
        return $this->belongsTo(Investment::class, 'investment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
