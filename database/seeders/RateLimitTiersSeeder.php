<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateLimitTiersSeeder extends Seeder
{
    public function run()
    {
        $tiers = [
            [
                'name' => 'bronze',
                'requests_per_minute' => 60,
                'burst_limit' => 5,
                'concurrent_requests' => 3,
                'endpoint_limits' => json_encode([
                    'default' => 60,
                    '/api/heavy-operation' => 30,
                    '/api/light-operation' => 120
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'silver',
                'requests_per_minute' => 300,
                'burst_limit' => 15,
                'concurrent_requests' => 10,
                'endpoint_limits' => json_encode([
                    'default' => 300,
                    '/api/heavy-operation' => 150,
                    '/api/light-operation' => 600
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'gold',
                'requests_per_minute' => 1000,
                'burst_limit' => 50,
                'concurrent_requests' => 25,
                'endpoint_limits' => json_encode([
                    'default' => 1000,
                    '/api/heavy-operation' => 500,
                    '/api/light-operation' => 2000
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('rate_limit_tiers')->insert($tiers);
    }
}
