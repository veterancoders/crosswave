<?php

namespace App\Models;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Filament\Resources\InvestmentResource;
use App\Notifications\NotificationSender;
use App\Observers\InvestmentObserver;
use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Localizable;
use Ramsey\Uuid\Rfc4122\TimeTrait;
use Spatie\Translatable\HasTranslations;

class Investment extends Model
{

    
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'wallet',
        'plan_id',
        'status',
        'can_reinvest',
        'payment_prove',
        'currency',
        'payout_amount',
        'start_date',
        'payout_date',

    ];


    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getUrlAttribute()
    {

        return InvestmentResource::getUrl('view', ['record' => $this]);
    }


    public function sendNotification($template_name)
    {

        $details = [
            'fields' => [
                'Customer' => optional($this->user)->email,
                'Amount' => $this->amount,
                'Plan name' => $this->plan->name,
                'Status' => optional($this->status),
            ],
            'shortcodes' => [
                '[CUSTOMER_NAME]' => optional($this->user)->name,
                '[PLAN_ID]' => $this->plan->name,
                '[INVESTMENT_URL]' => $this->url,
            ]
        ];



        if (!is_null($this->user)) {
            $this->user->notify(new NotificationSender($template_name, $details));
        }
    }

}
