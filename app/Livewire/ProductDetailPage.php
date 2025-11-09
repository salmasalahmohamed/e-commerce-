<?php

namespace App\Livewire;

use App\Helper\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class ProductDetailPage extends Component
{
    public $slug;
    public $product=[];
    public $quantity=1;
    public $total_count;
    public function mount($slug){
        $this->slug=$slug;
        $this->product=Product::where('slug',$slug)->first();


    }
    public function increaseQty(){
        $this->quantity++;
        CartManagement::incrementQuantityToCartItem($this->product->id);
    }
    public function decreaseQty(){
        if ($this->quantity>1){
            $this->quantity--;
            CartManagement::decrementQuantityToCartItem($this->product->id);

        }


    }
    public function addToCart($id){
        $total_count=CartManagement::addItemToCart($id);
        $this->dispatch('update-cart-count',total_count:$total_count)->to(Navbar::class);
        LivewireAlert::title('cart add')
            ->text('you add the item to cart successfully.')
            ->position('top')
            ->timer(3000)
            ->show();


    }

    public function render()
    {
        return view('livewire.product-detail-page');
    }
}
