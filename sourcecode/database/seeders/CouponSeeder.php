<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // Giảm 10%
        Coupon::create([
            'code'        => 'GIAM10',
            'type'        => 'percent',
            'value'       => 10,
            'min_order'   => 500000,
            'usage_limit' => 100,
            'expires_at'  => '2026-12-31',
            'is_active'   => true,
        ]);

        // Giảm 50k
        Coupon::create([
            'code'        => 'GIAM50K',
            'type'        => 'fixed',
            'value'       => 50000,
            'min_order'   => 300000,
            'usage_limit' => 50,
            'expires_at'  => '2026-12-31',
            'is_active'   => true,
        ]);

        // Giảm 20% không giới hạn
        Coupon::create([
            'code'        => 'SALE20',
            'type'        => 'percent',
            'value'       => 20,
            'min_order'   => 1000000,
            'usage_limit' => null,
            'expires_at'  => null,
            'is_active'   => true,
        ]);
    }
}