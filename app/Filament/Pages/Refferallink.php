<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;
class Refferallink extends Page implements HasForms
{

    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.refferallink';
    protected static function shouldRegisterNavigation(): bool
    {

        return !isAdmin();
    }

    protected static ?string $navigationGroup = 'Refferals';
    protected static ?string $navigationLabel = 'Refferal Link';
    public function mount(): void 
    {   $Refferal = env('APP_URL').'?ref='. Auth::user()->refferal_code;
        $this->form->fill([
            'refferallink' => $Refferal,
        ]);
    } 

    protected function getFormSchema(): array 
    {   

        return [

        
            Section::make('Refferal Program')->schema([
                ViewField::make('Usage')->view('filament.pages.refferalinfo'),
                TextInput::make('refferallink')->disabled()->label('Your Refferal Link')
                ->suffixAction(CopyAction::make()),

                ViewField::make('Rules')->view('filament.pages.refferalinfo2'),
            ]),
          
           
        ];
    } 
 
}
