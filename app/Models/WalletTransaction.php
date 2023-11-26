<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
    public $table = 'wallet_transactions';
    protected $fillable = [
        'amount',
        'user_id',
        'currency_code',
        'ref',
        'reason',
        'session_id',
        'wallet',
        'payment_method_id',
        'status',
        'is_credit',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
