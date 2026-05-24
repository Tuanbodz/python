<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'             => 'Casio MTP-V001L-1B',
                'slug'             => 'casio-mtp-v001l-1b',
                'category_id'      => 1, // Đồng hồ nam
                'brand_id'         => 1, // Casio
                'description'      => 'Đồng hồ nam Casio dây da sang trọng',
                'price'            => 850000,
                'sale_price'       => 750000,
                'thumbnail'        => 'products/casio-mtp-v001l.jpg',
                'stock'            => 50,
                'movement'         => 'Quartz',
                'water_resistance' => '30m',
                'case_size'        => '40mm',
                'strap_material'   => 'Da',
                'case_material'    => 'Thép không gỉ',
                'dial_color'       => 'Đen',
                'is_active'        => true,
                'is_featured'      => true,
            ],
            [
                'name'             => 'Seiko SNK809',
                'slug'             => 'seiko-snk809',
                'category_id'      => 1,
                'brand_id'         => 2, // Seiko
                'description'      => 'Đồng hồ cơ tự động Seiko 5',
                'price'            => 3200000,
                'sale_price'       => null,
                'thumbnail'        => 'products/seiko-snk809.jpg',
                'stock'            => 20,
                'movement'         => 'Automatic',
                'water_resistance' => '30m',
                'case_size'        => '37mm',
                'strap_material'   => 'Vải Canvas',
                'case_material'    => 'Thép không gỉ',
                'dial_color'       => 'Xanh',
                'is_active'        => true,
                'is_featured'      => true,
            ],
            [
                'name'             => 'Citizen BI5050-54L',
                'slug'             => 'citizen-bi5050-54l',
                'category_id'      => 2, // Đồng hồ nữ
                'brand_id'         => 3, // Citizen
                'description'      => 'Đồng hồ nữ Citizen Eco-Drive',
                'price'            => 4500000,
                'sale_price'       => 3900000,
                'thumbnail'        => 'products/citizen-bi5050.jpg',
                'stock'            => 15,
                'movement'         => 'Eco-Drive',
                'water_resistance' => '50m',
                'case_size'        => '29mm',
                'strap_material'   => 'Kim loại',
                'case_material'    => 'Thép không gỉ',
                'dial_color'       => 'Trắng',
                'is_active'        => true,
                'is_featured'      => false,
            ],
            [
                'name'             => 'Fossil FS5380',
                'slug'             => 'fossil-fs5380',
                'category_id'      => 1,
                'brand_id'         => 5, // Fossil
                'description'      => 'Đồng hồ nam Fossil dây da nâu',
                'price'            => 5200000,
                'sale_price'       => 4500000,
                'thumbnail'        => 'products/fossil-fs5380.jpg',
                'stock'            => 10,
                'movement'         => 'Quartz',
                'water_resistance' => '50m',
                'case_size'        => '44mm',
                'strap_material'   => 'Da',
                'case_material'    => 'Thép không gỉ',
                'dial_color'       => 'Nâu',
                'is_active'        => true,
                'is_featured'      => true,
            ],
            [
                'name'             => 'Daniel Wellington Classic',
                'slug'             => 'daniel-wellington-classic',
                'category_id'      => 2,
                'brand_id'         => 6, // DW
                'description'      => 'Đồng hồ nữ Daniel Wellington thanh lịch',
                'price'            => 3800000,
                'sale_price'       => null,
                'thumbnail'        => 'products/dw-classic.jpg',
                'stock'            => 25,
                'movement'         => 'Quartz',
                'water_resistance' => '30m',
                'case_size'        => '32mm',
                'strap_material'   => 'Da',
                'case_material'    => 'Thép không gỉ',
                'dial_color'       => 'Trắng',
                'is_active'        => true,
                'is_featured'      => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}