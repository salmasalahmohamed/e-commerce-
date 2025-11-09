<?php

namespace App\Livewire;

use App\Helper\CartManagement;
use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutPage extends Component
{
    #[Title('Checkout')]
    public $first_name;
    public $last_name;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $payment_method;
    public function mount(){
        $cart_items=CartManagement::getCartItemsFromCookie();
        if (count($cart_items)==0){
            return redirect('/products');
        }
    }
public function save(){
$this->validate([
'first_name'=>'required',
    'last_name'=>'required',
    'phone'=>'required',
    'address'=>'required',
    'city'=>'required',
    'state'=>'required',
    'zip'=>'required',
   'payment_method'=>'required',
]);
    DB::beginTransaction();

    try {


        $cart_items=CartManagement::getCartItemsFromCookie();
        foreach ($cart_items as $item){
            $line_items[]=[
                'price_data'=>[
                    'currency'=>'EGP',
                    'unit_amount'=>$item['unit_amount']*100,
                    'product_data'=>[

                        'name'=>$item['product_name'],

                    ]

                ],
                'quantity'=>$item['quantity'],
            ];

        }
        $order=new Order();
        $order->user_id=auth()->user()->id;
        $order->grand_total=CartManagement::calculateGrandTotal($cart_items);
        $order->payment_method=$this->payment_method;
        $order->payment_status='pending';
        $order->status='new';
        $order->currency='EGP';
        $order->shipping_amount=0;
        $order->shipping_method='none';
        $order->notes='order placed by '.auth()->user()->name;
        $order->save();
        $address=Address::create([
            'order_id'=>$order->id,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'phone'=>$this->phone,
            'street_address'=>$this->address,
            'city'=>$this->city,
            'state'=>$this->state,
            'zip_code'=>$this->zip,

        ]);
        $order->items()->createMany( collect($cart_items)->map(function ($item) {
            unset($item['product_name']);
            unset($item['image']);


            return $item;
        })->toArray());
        $redirect_url='';
        if ($this->payment_method=='stripe'){
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionCheckout=Session::create([
                'payment_method_types'=>['card'],
                'customer_email'=>auth()->user()->email,
                'line_items'=>$line_items,
                'mode'=>'payment',
                'success_url'=>route('success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'=>route('cancel'),

            ]);
            $redirect_url=$sessionCheckout->url;

        }else{
            $redirect_url=route('success');
        }
        CartManagement::clearCarItemsFromCookie();
//        Mail::to($order->user_id)->send(new OrderPlaced($order));

        DB::commit();
        return redirect($redirect_url);


    } catch (\Throwable $e) {

        DB::rollBack();
        throw $e;
    }



}
    public function render()
    {
        $cart_items=CartManagement::getCartItemsFromCookie();
        $grand_total=CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page',['cart_items'=>$cart_items,'grand_total'=>
        $grand_total]);
    }
}
