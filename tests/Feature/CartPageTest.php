<?php

namespace Tests\Feature;

use App\Helper\CartManagement;
use App\Livewire\CartPage;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class CartPageTest extends TestCase{

    public function test_remove_item_updates_cart_and_grand_total()
    {


        $cart_items = [
            [
                'product_id' => 1,
                'product_name' => 'test product',
                'quantity' => 1,
                'unit_amount' => 150,
                'total_amount' => 150,
                'image' => 'img.png',
            ],
            [
                'product_id' => 2,
                'product_name' => 'test2 product',
                'quantity' => 1,
                'unit_amount' => 150,
                'total_amount' => 150,
                'image' => 'img2.png',
            ],

        ];


        $fakeCartAfterRemove = [
            [
                'product_id' => 2,
                'product_name' => 'test2 product',
                'quantity' => 1,
                'unit_amount' => 150,
                'total_amount' => 150,
                'image' => 'img2.png',
            ],        ];

        $cartMock = Mockery::mock('alias:' . CartManagement::class);

        $cartMock->shouldReceive('getCartItemsFromCookie')
            ->andReturn($cart_items);

        $cartMock->shouldReceive('calculateGrandTotal')
            ->with($cart_items)
            ->andReturn(250);

        $cartMock->shouldReceive('removeCartItem')
            ->with(1)
            ->andReturn($fakeCartAfterRemove);

        $cartMock->shouldReceive('calculateGrandTotal')
            ->with($fakeCartAfterRemove)
            ->andReturn(150);


        Livewire::test(CartPage::class)
            ->assertSet('cart_items', $cart_items)
            ->assertSet('grand_total', 250)
            ->call('removeItem', 1)
            ->assertSet('cart_items', $fakeCartAfterRemove)
            ->assertSet('grand_total', 150);
    }
}
