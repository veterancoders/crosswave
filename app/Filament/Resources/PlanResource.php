<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Currency;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Transactions';

    
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Plan Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(250),
                        RichEditor::make('description')->fileAttachmentsDirectory('attachments')
                            ->required()
                            ->maxLength(65535),

                    ])->columnSpan(2),
                Section::make('Pricing')
                    ->schema([

                        Forms\Components\TextInput::make('price')->integer()
                            ->helperText('Set Plan price.')
                            ->required(),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('signup_fee')->integer()
                                    ->helperText('Set Plan signup fee. Do not use decimal values to avoid error.')
                                    ->required(),
                                Forms\Components\TextInput::make('profit_percent')->integer()
                                    ->required()->prefix('%'),
                            ])


                    ])->columnSpan(2),
                Section::make('Invoice')
                    ->schema([

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('invoice_period')
                                    ->required()->numeric(),
                                Select::make('invoice_interval')
                                    ->options([
                                        'Day' => 'Day',
                                        'Week' => 'Week',
                                        'Month' => 'Month',
                                    ])->required()->helperText('Set Invoice Interval / Payout period'),
             
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('trial_period')->numeric()
                                    ->required(),
                                    Select::make('invoice_interval')
                                    ->options([
                                        'Day' => 'Day',
                                        'Week' => 'Week',
                                        'Month' => 'Month',
                                    ])->helperText('Set Invoice Interval / Payout period')->required(),
                            ])
                    ])->columnSpan(2)->collapsible(),
                Section::make('Additional Information')
                    ->schema([

                        Forms\Components\TextInput::make('sort_order')
                            ->required()->numeric(),

                        Select::make('currency')

                            ->options(Currency::all()->pluck('name', 'code'))
                            ->searchable(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(250),


                    ])->columnSpan(2)->collapsible(),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
      

        $currency = Currency::where('code',  CountryCode())->first();
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('price')->toggleable()->prefix($currency->symbol),
                Tables\Columns\TextColumn::make('signup_fee')->toggleable()->prefix($currency->symbol),
                Tables\Columns\TextColumn::make('invoice_period')->toggleable(),
                Tables\Columns\TextColumn::make('invoice_interval'),
                Tables\Columns\TextColumn::make('trial_period'),
                Tables\Columns\TextColumn::make('trial_interval'),

                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
              
            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest();
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    protected function getRedirectUrl(): string
    {
        return PlanResource::getUrl('index');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
            'view' => Pages\ViewPlan::route('/{record}'),
        ];
    }
}
