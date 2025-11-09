<?php

namespace App\Livewire;

use App\Helper\CartManagement;
use App\Livewire\Partials\Navbar;
use Livewire\Component;

class CartPage extends Component
{
    public $cart_items=[];
    public $grand_total;
    public function mount(){
        $this->cart_items=CartManagement::getCartItemsFromCookie();
        $this->grand_total=CartManagement::calculateGrandTotal($this->cart_items);
    }
    public function removeItem($id){
        $this->cart_items= CartManagement::removeCartItem($id);
        $this->grand_total=CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count',total_count:count($this->cart_items))->to(Navbar::class);

    }
    public function increaseQty($id){
       $this->cart_items= CartManagement::incrementQuantityToCartItem($id);
        $this->grand_total=CartManagement::calculateGrandTotal($this->cart_items);

    }
    public function decreaseQty($id){

        $this->cart_items=    CartManagement::decrementQuantityToCartItem($id);
        $this->grand_total=CartManagement::calculateGrandTotal($this->cart_items);



    }
    public function render()
    {
        return view('livewire.cart-page');
    }
}
