<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', '');
        $this->migrator->add('general.site_active', true);
        $this->migrator->add('general.default_currency', 'USD'); 
        $this->migrator->add('general.transfer_rate', 0);
    
    }
}
