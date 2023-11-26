<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferhistory extends Model
{
    use HasFactory;

    public $table = 'transferhistory';
 
    protected $fillable = [
        'user_id',
        'reciepient_id',
        'transfer_id',
        'transfer_from',
        'transfer_to',
        'amount',
        'status'
     
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reciepient()
    {
        return $this->belongsTo(User::class, 'reciepient_id');
    }
}
