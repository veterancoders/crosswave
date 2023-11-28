<?php

namespace App\Filament\Widgets;

use App\Models\Currency;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\ViewField;

class Convert extends Widget implements HasForms
{
    use InteractsWithForms;

    public $Convert = '';
    public $Rate = '';
    public $currency = '';
    public $To = '';


    public function render(): View
    {
        return view('convert');
    }
    protected function getColumns(): int | array
    {
        return 1;
    }
    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {



        $exchangeRates = app(ExchangeRate::class);

        /*  $result = $exchangeRates->exchangeRate('USD', 'EUR'); */
        $result = $exchangeRates->convert(100, 'NGN', 'USD', Carbon::now());


        $currency_code =  CountryCode();
        return [
            Section::make('Convert')
                ->description('Convert currency')
                ->schema([

                    Grid::make(2)->schema([
                        TextInput::make('Convert')->prefix($currency_code)->reactive()->afterStateUpdated(function ($state, callable $set) {

                            session(['convertfrom' => $state]);


                            $exchangeRates = app(ExchangeRate::class);
                            $currency_code =  CountryCode();
                            $currencyconvertto =   session('currencyconvertto');

                            if ($state != 0) {


                                if (!is_null($currencyconvertto)) {
                                    $rate = $exchangeRates->convert($state, $currency_code, $currencyconvertto, Carbon::now());
                                    $set('To', $rate);

                                    $birate = $exchangeRates->exchangeRate($currency_code, $currencyconvertto);
                                    $set('Rate', $birate);
                                }
                            }
                        })->default(0.00),
                        Select::make('currency')
                            ->label('To')
                            ->options(Currency::all()->pluck('name', 'code'))->default(session('currencyconvertto'))->disablePlaceholderSelection()->reactive()->afterStateUpdated(function ($state, callable $set) {
                                session(['currencyconvertto' => $state]);

                                $exchangeRates = app(ExchangeRate::class);
                                $currency_code =  CountryCode();
                                $rate = $exchangeRates->exchangeRate($currency_code, $state);
                                $convertfrom =   session('convertfrom');

                                $newrate = $exchangeRates->convert($convertfrom, $currency_code, $state, Carbon::now());
                                $set('To', $newrate);
                                $set('Rate', $rate);
                            }),
                    ]),

                    Fieldset::make('Check Rate')->schema([
                        TextInput::make('From')->disabled()->default(1)->prefix($currency_code),
                        TextInput::make('Rate')->disabled()->prefix(session('currencyconvertto')),
                    ]),
                  
                    TextInput::make('To')->label('Amount')->prefix(session('currencyconvertto'))->default(0.00),
                ])->collapsible()

        ];
    }
}
