<?php

namespace App\Settings;


use Spatie\LaravelSettings\Settings;

class DepositSettings extends Settings
{
    public string $bitcoin_address;
    public string $bitcoin_address_barcode;

    public static function group(): string
    {
        return 'deposit';
    }
}
