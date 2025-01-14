<?php

declare(strict_types=1);

namespace App\Domains\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class DomainRegistrationServiceProvider extends ServiceProvider
{
    /**
     * Register domain services.
     */
    public function register(): void
    {
        $this->registerDomainProviders();
    }

    /**
     * Bootstrap domain services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register all domain service providers.
     */
    private function registerDomainProviders(): void
    {
        $domainsPath = app_path('Domains');
        
        // Get all directories in the Domains folder except Shared
        $domains = collect(File::directories($domainsPath))
            ->filter(fn ($path) => !str_ends_with($path, 'Shared'))
            ->map(fn ($path) => basename($path));

        foreach ($domains as $domain) {
            // Check if the domain has a Providers directory
            $providersPath = $domainsPath . '/' . $domain . '/Providers';
            
            if (File::isDirectory($providersPath)) {
                // Get all PHP files in the Providers directory
                $providers = File::glob($providersPath . '/*.php');
                
                foreach ($providers as $provider) {
                    // Convert file path to fully qualified class name
                    $className = 'App\\Domains\\' . $domain . '\\Providers\\' . basename($provider, '.php');
                    
                    if (class_exists($className)) {
                        $this->app->register($className);
                    }
                }
            }
        }
    }
}
