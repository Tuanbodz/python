<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->decimal('price', 12, 0);
            $table->decimal('sale_price', 12, 0)->nullable();
            $table->string('thumbnail');
            $table->integer('stock')->default(0);
            $table->integer('sold')->default(0);
            $table->string('dial_color')->nullable();
            $table->string('case_material')->nullable();
            $table->string('strap_material')->nullable();
            $table->string('movement')->nullable();
            $table->string('water_resistance')->nullable();
            $table->string('case_size')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};