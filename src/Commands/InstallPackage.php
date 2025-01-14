<?php

namespace Ajz\ApiBase\Commands;

use Illuminate\Console\Command;

class InstallPackage extends Command
{
    protected $signature = 'api-base:install';
    protected $description = 'Install the API Base package';

    public function handle()
    {
        // Publish configuration
        $this->call('vendor:publish', [
            '--provider' => 'VendorName\ApiBase\Providers\ApiBaseServiceProvider',
            '--tag' => 'api-base-config'
        ]);

        // Run migrations
        $this->call('migrate');

        // Run seeders
        $this->call('db:seed', [
            '--class' => 'VendorName\ApiBase\Database\Seeders\RateLimitTiersSeeder'
        ]);

        $this->info('API Base package has been installed successfully!');
    }
}
