<?php

namespace App\Filament\Widgets;

use App\Models\Currency;
use App\Models\WalletTransaction as ModelsWalletTransaction;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class WalletTransaction extends BaseWidget
{
   /*  protected int | string | array $columnSpan = 'full'; */
    protected function getTableQuery(): Builder
    {
        return ModelsWalletTransaction::query()->where('user_id', auth()->id())->latest();
    }
    protected function getColumns(): int | array
    {
        return 1;
    }
    protected function getTableColumns(): array
    {

        $currency_code =  CountryCode();

        $currency = Currency::where('code', $currency_code)->first();
        return [
            TextColumn::make('user.email')->searchable()->visible(isAdmin()),
            TextColumn::make('reason')->label('Transaction'),
            TextColumn::make('amount')->prefix($currency->symbol),
            TextColumn::make('wallet')->searchable(),
            BadgeColumn::make('status')->label('Status')->searchable()
                ->colors([
                    'primary',
                    'success'  => static fn ($state): bool => $state === 'Successful',
                    'danger' => static fn ($state): bool => $state === 'Unsuccessful',
                ]),
            TextColumn::make('created_at')
                ->dateTime(),

        ];
    }
}
