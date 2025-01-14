<?php

declare(strict_types=1);

namespace App\Domains\Shared\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

final class DomainRouteServiceProvider extends ServiceProvider
{
    /**
     * Register domain routes.
     */
    public function register(): void
    {
        $this->loadDomainRoutes();
    }

    /**
     * Load routes from all domain directories.
     */
    protected function loadDomainRoutes(): void
    {
        $domainsPath = app_path('Domains');
        
        if (!File::isDirectory($domainsPath)) {
            return;
        }

        // Get all domain directories
        $domains = File::directories($domainsPath);

        foreach ($domains as $domainPath) {
            $this->registerDomainRoutes($domainPath);
        }
    }

    /**
     * Register routes for a specific domain.
     */
    protected function registerDomainRoutes(string $domainPath): void
    {
        $domainName = basename($domainPath);
        
        // Skip the Shared domain as it's not meant to have routes
        if ($domainName === 'Shared') {
            return;
        }

        // Register API routes
        $apiRoutesPath = "{$domainPath}/routes/api.php";
        if (File::exists($apiRoutesPath)) {
            Route::middleware('api')
                ->prefix('api')
                ->group($apiRoutesPath);
        }

        // Special case for Auth domain OAuth routes
        if ($domainName === 'Auth') {
            $oauthRoutesPath = "{$domainPath}/routes/oauth.php";
            if (File::exists($oauthRoutesPath)) {
                Route::middleware('api')
                    ->prefix('oauth')
                    ->group($oauthRoutesPath);
            }
        }
    }
}
