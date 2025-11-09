<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderDetailPage extends Component
{
    public $order;
    public function mount($order){
        $this->order=Order::where('id',$order)->with(['Items','address'])->get();


    }
    public function render()
    {
        return view('livewire.order-detail-page',['order'=>$this->order]);
    }
}
