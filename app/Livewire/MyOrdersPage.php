<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class MyOrdersPage extends Component
{
    public function render()
    {
        $my_order=Order::with('address')->where('user_id',auth()->user()->id)->get();

        return view('livewire.my-orders-page',['orders'=>$my_order]);
    }
}
