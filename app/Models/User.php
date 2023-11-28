<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use JeffGreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWallets;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable implements MustVerifyEmail, Wallet, WalletFloat
{
    use HasRoles, HasWallet, HasWalletFloat, HasWallets, SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

/*   public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar;
    }  */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'country',
        'currency',
        'phone',
        'phonecode',
        'gender',
        'bio',
        'dob',
        'address',
        'zipcode',
        'currency',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'avatar' => 'array',
    ];
}
