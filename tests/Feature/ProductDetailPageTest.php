<?php

namespace Tests\Feature;

use App\Helper\CartManagement;
use App\Livewire\ProductDetailPage;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class ProductDetailPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_mount_sets_product_based_on_slug()
    {
        $product = Product::factory()->create([
            'slug' => 'my-product'
        ]);

        Livewire::test(ProductDetailPage::class, ['slug' => 'my-product'])
            ->assertSet('product.id', $product->id)
            ->assertSet('quantity', 1);
    }

    public function test_increase_quantity_calls_cart_helper()
    {
        $product = Product::factory()->create([
            'slug' => 'my-product'
        ]);

        $mock = Mockery::mock('alias:' . CartManagement::class);
        $mock->shouldReceive('incrementQuantityToCartItem')
            ->once()
            ->with($product->id);

        Livewire::test(ProductDetailPage::class, ['slug' => 'my-product'])
            ->call('increaseQty')
            ->assertSet('quantity', 2);
    }

    public function test_decrease_quantity_calls_cart_helper()
    {
        $product = Product::factory()->create([
            'slug' => 'my-product'
        ]);

        $mock = Mockery::mock('alias:' . CartManagement::class);
        $mock->shouldReceive('decrementQuantityToCartItem')
            ->once()
            ->with($product->id);

        Livewire::test(ProductDetailPage::class, ['slug' => 'my-product'])
            ->set('quantity', 2)
            ->call('decreaseQty')
            ->assertSet('quantity', 1);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
