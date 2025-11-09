<?php

namespace Tests\Feature;

use App\Livewire\ProductsPage;
use App\Models\Product;
use Livewire\Livewire;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ProductsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_renders_products_page_component()
    {
        Livewire::test(ProductsPage::class)
            ->assertStatus(200);
    }

    public function test_it_loads_only_active_products()
    {
        Product::factory()->create(['is_active' => 1]);
        Product::factory()->create(['is_active' => 0]);

        Livewire::test(ProductsPage::class)
            ->call('loadProducts')
            ->assertCount('products', 1);
    }

    public function test_filter_by_category()
    {
        $cat1 = \App\Models\Category::factory()->create();
        $cat2 = \App\Models\Category::factory()->create();

        Product::factory()->create(['category_id'=>$cat1->id,'is_active'=>1]);
        Product::factory()->create(['category_id'=>$cat2->id,'is_active'=>1]);

        Livewire::test(ProductsPage::class)
            ->set('filterByCategory', [$cat1->id])
            ->call('loadProducts')
            ->assertCount('products', 1);
    }
    public function test_user_can_add_product_to_cart()
    {
        $product = Product::factory()->create();

        $mock = Mockery::mock('alias:' . \App\Helper\CartManagement::class);
        $mock->shouldReceive('addItemToCart')
            ->with($product->id)
            ->andReturn(5);

        Livewire::test(ProductsPage::class)
            ->call('addToCart', $product->id)
            ->assertDispatched('update-cart-count', total_count:5);
    }
}
