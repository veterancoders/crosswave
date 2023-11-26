<?php

namespace App\Filament\Widgets;

use App\Models\Currency;
use App\Models\Investment;
use App\Models\Plan;
use App\Models\User;
use App\Models\Wallet;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class LatestInvestments extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';
    protected function getTableQuery(): Builder
    {
        if(isAdmin()){
            return Investment::query()->latest();

        }else{
            return Investment::query()->where('user_id', auth()->id())->latest();

        }
    }
 
    protected function getTableColumns(): array
    {   $currency = Currency::where('code', CountryCode())->first();
        return [
            Tables\Columns\TextColumn::make('user_id')->label('User name')->visible(isAdmin())->weight('bold')->formatStateUsing(fn (string $state): string => __( User::find($state)->name)),
            Tables\Columns\TextColumn::make('plan_id')->label('Plan Name')->searchable()->weight('bold')->formatStateUsing(fn (string $state): string => __( Plan::find($state)->name)),
            Tables\Columns\TextColumn::make('amount')->weight('bold')->prefix($currency->symbol),

            BadgeColumn::make('status')->label('Status')->searchable()
                ->colors([
                    'primary',
                    'success'  => static fn ($state): bool => $state === 'approved',
                    'danger' => static fn ($state): bool => $state === 'cancelled',
                    'warning' => static fn ($state): bool => $state === 'pending',
                ]),
            Tables\Columns\TextColumn::make('created_at')->label('Date Created')
                ->date(),
            Tables\Columns\TextColumn::make('payout_date')
                ->date(),
        ];
    }
}
