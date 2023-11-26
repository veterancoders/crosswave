<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refferal extends Model
{
    use HasFactory;

    public $table = 'refferals';
    protected $fillable = [
        'user_id',
        'reffered_user_id',
        'profit',
        'has_completed_transaction',
        'confirmed',
     
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    
    public function reffereduser()
    {
        return $this->belongsTo(User::class, 'reffered_user_id');
    }
}
