<?php

namespace App\Filament\Pages;

use App\Models\Transferhistory as ModelsTransferhistory;
use Filament\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class TransferHistory extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.transfer-history';

    protected static ?string $navigationGroup = 'Transfers';

    protected function getTableQuery(): Builder
    {

        if(isAdmin()){
            return ModelsTransferhistory::query();
        }else{

            return ModelsTransferhistory::query()->where('user_id', auth()->id());
        }
 
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('transfer_id')->weight('bold')->searchable(),
            TextColumn::make('user.email')->searchable(),
            TextColumn::make('reciepient.email')->searchable(),
            TextColumn::make('transfer_from'),
            TextColumn::make('transfer_to'),
            TextColumn::make('amount'),
            BadgeColumn::make('status')->label('Status')->searchable()
                ->colors([
                    'primary',
                    'success'  => static fn ($state): bool => $state === 'Successful',
                    'danger' => static fn ($state): bool => $state === 'Unsuccessful',
                ]),
            TextColumn::make('created_at'),
        ];
    }
}
