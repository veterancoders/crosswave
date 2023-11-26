<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefferalResource\Pages;
use App\Filament\Resources\RefferalResource\RelationManagers;
use App\Models\Currency;
use App\Models\Refferal;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RefferalResource extends Resource
{
    protected static ?string $model = Refferal::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Refferals';
    
    protected static ?string $navigationLabel = 'Refferals';
    public static function form(Form $form): Form
    {

        
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id'),
                Forms\Components\TextInput::make('reffered_user_id'),
                Forms\Components\TextInput::make('amount'),
                Forms\Components\Toggle::make('confirmed'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $currency = Currency::where('code', CountryCode())->first();
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')->visible(isAdmin())->searchable(),
                Tables\Columns\TextColumn::make('reffereduser.email')->searchable()->label('Reffered User'),
                Tables\Columns\TextColumn::make('profit')->prefix($currency->symbol),
                Tables\Columns\BooleanColumn::make('confirmed'),Tables\Columns\BooleanColumn::make('has_completed_transaction'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label('Date Created'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                
                Tables\Actions\DeleteAction::make()->visible(isAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->visible(isAdmin()),
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
        if(isAdmin()){
            return parent::getEloquentQuery()->latest();

        }else{

            return parent::getEloquentQuery()->latest()->where('user_id', auth()->id());

        }
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRefferals::route('/'),
            'create' => Pages\CreateRefferal::route('/create'),
            'edit' => Pages\EditRefferal::route('/{record}/edit'),
        ];
    }    
}
