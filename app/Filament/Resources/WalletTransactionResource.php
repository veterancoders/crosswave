<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletTransactionResource\Pages;
use App\Filament\Resources\WalletTransactionResource\RelationManagers;
use App\Models\Currency;
use App\Models\WalletTransaction;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Wallets';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required(),
               
                Forms\Components\TextInput::make('ref')
                    ->maxLength(250),
                Forms\Components\TextInput::make('reason')
                    ->maxLength(250),
                Forms\Components\TextInput::make('session_id')
                    ->maxLength(250),
                Forms\Components\TextInput::make('wallet_id')
                    ->required(),
                Forms\Components\TextInput::make('payment_method_id'),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(250),
                Forms\Components\Toggle::make('is_credit')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {  

       
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')->searchable()->visible(isAdmin()),
                Tables\Columns\TextColumn::make('reason')->label('Transaction'),
                Tables\Columns\TextColumn::make('currency_code'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('wallet')->searchable(),
                BadgeColumn::make('status')->label('Status')->searchable()
                ->colors([
                    'primary',
                    'success'  => static fn ($state): bool => $state === 'Successful',
                    'danger' => static fn ($state): bool => $state === 'Unsuccessful',
                ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
           
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        if (isAdmin()) {
            return parent::getEloquentQuery()->latest();
        } elseif (isCustomer()) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->id())->latest();
        }
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWalletTransactions::route('/'),
            'create' => Pages\CreateWalletTransaction::route('/create'),
            'edit' => Pages\EditWalletTransaction::route('/{record}/edit'),
        ];
    }    
}
