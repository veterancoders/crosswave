<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationTemplateResource\Pages;
use App\Filament\Resources\NotificationTemplateResource\RelationManagers;
use App\Models\NotificationTemplate;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


class NotificationTemplateResource extends Resource
{



    protected static ?string $navigationGroup = 'Setup';
    protected static function shouldRegisterNavigation(): bool
    {

        return isAdmin();
    }


    protected static ?string $model = NotificationTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Fieldset::make('Email Head')->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxLength(200),
                        Forms\Components\TextInput::make('greeting')
                            ->maxLength(65535),
                        Forms\Components\TextInput::make('subject')
                            ->maxLength(65535),
                    ]),

                    Fieldset::make('Email Body')->schema([
                        Forms\Components\Textarea::make('sms_body')
                            ->maxLength(65535),
                        RichEditor::make('email_body')
                            ->maxLength(65535)->toolbarButtons([
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'edit',
                                'italic',
                                'link',
                                'orderedList',
                                'preview',
                                'strike',
                            ]),

                        Forms\Components\Textarea::make('database_body')
                            ->maxLength(65535),

                    ]),
                ]),

                Card::make()->schema([
                    Forms\Components\Textarea::make('help')
                        ->maxLength(65535),


                    Forms\Components\Textarea::make('thanks')
                        ->maxLength(65535),
                    Forms\Components\TextInput::make('action_text')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('action_url')
                        ->maxLength(255),
                    CheckboxList::make('enabled_channels')
                        ->options([
                            'mail' => 'Email'

                        ])->columns(2),
                    Forms\Components\Toggle::make('active'),

                    Forms\Components\Textarea::make('webpush_body')
                        ->maxLength(65535),
                ]),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\BooleanColumn::make('active'),
                Tables\Columns\TextColumn::make('action_url'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
               
            ])
            ->actions([

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
             
                ]),

            ])
            ->filters([
                //
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
            'index' => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit' => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
