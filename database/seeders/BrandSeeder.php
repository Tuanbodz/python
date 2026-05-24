<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Casio',   'slug' => 'casio',   'description' => 'Thương hiệu đồng hồ Nhật Bản'],
            ['name' => 'Seiko',   'slug' => 'seiko',   'description' => 'Thương hiệu đồng hồ Nhật Bản cao cấp'],
            ['name' => 'Citizen', 'slug' => 'citizen', 'description' => 'Đồng hồ Nhật Bản bền đẹp'],
            ['name' => 'Orient',  'slug' => 'orient',  'description' => 'Đồng hồ cơ Nhật Bản'],
            ['name' => 'Fossil',  'slug' => 'fossil',  'description' => 'Thương hiệu đồng hồ Mỹ'],
            ['name' => 'Daniel Wellington', 'slug' => 'daniel-wellington', 'description' => 'Đồng hồ thời trang Thuỵ Điển'],
        ];

        foreach ($brands as $brand) {
            Brand::create(array_merge($brand, ['is_active' => true]));
        }
    }
}