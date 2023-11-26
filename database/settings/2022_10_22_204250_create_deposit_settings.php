<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateDepositSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('deposit.bitcoin_address', '');
        $this->migrator->add('deposit.bitcoin_address_barcode', '');
    }
}
