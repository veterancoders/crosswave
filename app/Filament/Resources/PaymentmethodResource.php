<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentmethodResource\Pages;
use App\Filament\Resources\PaymentmethodResource\RelationManagers;
use App\Models\Paymentmethod;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentmethodResource extends Resource
{
    protected static ?string $model = Paymentmethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Payment method';
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest();
    }
    protected static ?string $navigationGroup = 'Settings';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Payment Method Details')->schema([
                    Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(250),
                    FileUpload::make('image'),

                Forms\Components\Toggle::make('is_active'),
                ]),
              
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\BooleanColumn::make('is_active'),
        
           
            ])
            ->filters([
                //
            ])
            ->actions([
         /*        Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]), */
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentmethods::route('/'),
            'create' => Pages\CreatePaymentmethod::route('/create'),
            'edit' => Pages\EditPaymentmethod::route('/{record}/edit'),
        ];
    }
}
