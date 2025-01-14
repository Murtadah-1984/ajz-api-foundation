<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CleanupExpiredKeys extends Command
{
    protected $signature = 'api:cleanup-keys';
    protected $description = 'Clean up expired API keys';

    public function handle()
    {
        $this->info('Starting cleanup of expired API keys...');

        $expiredKeys = DB::table('api_keys')
            ->where('expires_at', '<', now())
            ->where('is_active', true)
            ->get(['key']);

        foreach ($expiredKeys as $key) {
            DB::table('api_keys')
                ->where('key', $key->key)
                ->update(['is_active' => false]);

            Cache::forget("api_key:{$key->key}");
            Cache::forget("rate_limit_config:{$key->key}");

            $this->line("Deactivated expired key: {$key->key}");
        }

        $count = $expiredKeys->count();
        $this->info("Cleanup completed. Processed {$count} expired keys.");
    }
}
