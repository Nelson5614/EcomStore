<?php

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('quantity');
            $table->boolean('is_published')->default(0);
            $table->boolean('instock')->default(0);
            $table->foreignIdFor(User::class, 'create_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
            $table->foreignIdFor(Brand::class, 'brand_id')->nullable();
            $table->foreignIdFor(Category::class, 'category_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
