<?php

namespace App\Livewire;

use App\Helper\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class ProductsPage extends Component
{
    #[Title('products-page')]
    public $products=[];
    public $categories=[];
    public $brands=[];
    #[Url]
    public $filterByStock ;
    #[Url]

    public $filterBySale ;
    #[Url]

    public $filterByCategory=[] ;
    #[Url]

    public $filterByBrand = [];
    #[Url]

    public $price ;

    #[Url]
 public $sort;

public function addToCart($id){
$total_count=CartManagement::addItemToCart($id);
$this->dispatch('update-cart-count',total_count:$total_count)->to(Navbar::class);
    LivewireAlert::title('cart add')
        ->text('you add the item to cart successfully.')
        ->position('top')
        ->timer(3000)
        ->show();


}
    public function loadProducts()
    {
        $query = Product::where('is_active', 1)->with(['brand', 'category']);

        if ($this->filterByStock) {
            $query->where('is_stock',true);
        }
        if(!empty($this->filterByCategory)){
            $query->whereIn('category_id', $this->filterByCategory);
        }
        if(!empty($this->filterByBrand)){
            $query->whereIn('brand_id', $this->filterByBrand);
        }
        if($this->price){
            $query->where('price', '<=', $this->price);
        }

        if ($this->filterBySale) {
            $query->where('is_sale',true);
        }
        if ($this->sort=='latest'){
            $query->latest();
        }
        if ($this->sort=='price'){
            $query->orderBy('price');
        }
        $this->products = $query->get();
    }


    public function render()
    {

        $this->loadProducts();

        $this->categories=Category::where('is_active',1)->get();
        $this->brands=Brand::where('is_active',1)->get();
        return view('livewire.products-page');
    }
}
