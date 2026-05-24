<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Danh mục cấp 1
        $nam = Category::create([
            'name'       => 'Đồng hồ nam',
            'slug'       => 'dong-ho-nam',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $nu = Category::create([
            'name'       => 'Đồng hồ nữ',
            'slug'       => 'dong-ho-nu',
            'is_active'  => true,
            'sort_order' => 2,
        ]);

        Category::create([
            'name'       => 'Đồng hồ treo tường',
            'slug'       => 'dong-ho-treo-tuong',
            'is_active'  => true,
            'sort_order' => 3,
        ]);

        // Danh mục cấp 2 (con của Đồng hồ nam)
        Category::create([
            'name'      => 'Đồng hồ nam dây da',
            'slug'      => 'dong-ho-nam-day-da',
            'parent_id' => $nam->id,
            'is_active' => true,
        ]);

        Category::create([
            'name'      => 'Đồng hồ nam dây kim loại',
            'slug'      => 'dong-ho-nam-day-kim-loai',
            'parent_id' => $nam->id,
            'is_active' => true,
        ]);

        // Danh mục cấp 2 (con của Đồng hồ nữ)
        Category::create([
            'name'      => 'Đồng hồ nữ dây da',
            'slug'      => 'dong-ho-nu-day-da',
            'parent_id' => $nu->id,
            'is_active' => true,
        ]);
    }
}