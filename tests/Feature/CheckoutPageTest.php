<?php

namespace Tests\Feature;

use App\Helper\CartManagement;
use App\Livewire\CheckoutPage;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class CheckoutPageTest extends TestCase
{
    public function test_checkout_creates_order_and_redirects()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 100,
        ]);
        $this->actingAs($user);

        $fakeCart = [
            [
                'product_id' => $product->id,
                'product_name' => 'Product AAA',
                'unit_amount' => $product->price,
                'quantity' => 2,
                'total_amount' => 200,
                'image' => 'img1.jpg'
            ]
        ];

        $cartMock = Mockery::mock('alias:' . CartManagement::class);
        $cartMock->shouldReceive('getCartItemsFromCookie')->andReturn($fakeCart);
        $cartMock->shouldReceive('calculateGrandTotal')->andReturn(200);
        $cartMock->shouldReceive('clearCarItemsFromCookie')->once();

        $sessionMock = (object)['url' => '/fake-stripe-url'];

        \Stripe\Stripe::setApiKey('fake');
        Mockery::mock('overload:\Stripe\Checkout\Session')
            ->shouldReceive('create')
            ->andReturn($sessionMock);

        Livewire::test(CheckoutPage::class)
            ->set('first_name','Salma')
            ->set('last_name','Mohamed')
            ->set('phone','01000')
            ->set('address','Street x')
            ->set('city','Cairo')
            ->set('state','Cairo')
            ->set('zip','12345')
            ->set('payment_method','stripe')
            ->call('save')
            ->assertRedirect('/fake-stripe-url');

        $this->assertDatabaseHas('orders',[
            'user_id'=>$user->id,
            'grand_total'=>200
        ]);
    }
}
