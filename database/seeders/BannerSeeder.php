<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title'      => 'Đồng hồ cao cấp giảm giá 20%',
                'image'      => 'banners/banner1.jpg',
                'link'       => '/san-pham',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'title'      => 'Bộ sưu tập đồng hồ nam mới nhất',
                'image'      => 'banners/banner2.jpg',
                'link'       => '/san-pham/dong-ho-nam',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'title'      => 'Đồng hồ nữ thời trang 2024',
                'image'      => 'banners/banner3.jpg',
                'link'       => '/san-pham/dong-ho-nu',
                'sort_order' => 3,
                'is_active'  => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}