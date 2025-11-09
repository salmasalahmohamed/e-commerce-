<?php

namespace App\Helper;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{

static public function addItemToCart($product_id){
    $cart_items=self::getCartItemsFromCookie();
    $existing_item=false;
        foreach ($cart_items as $key=>$item){
            if ($item['product_id']==$product_id){
                $existing_item=$key;
                break;
            }

    }
    if ($existing_item !== false){
        $cart_items[$existing_item]['quantity']++;
        $cart_items[$existing_item]['total_amount']=$cart_items[$existing_item]['quantity']*$cart_items[$existing_item]['unit_amount'];

    }else{
        $product=Product::query()->where('id',$product_id)->first(['id','name','price','image']);
        if ($product){
            $cart_items[]=[
'product_id'=>$product->id,
                'product_name'=>$product->name,
                'quantity'=>1,
                'unit_amount'=>$product->price,
                'total_amount'=>$product->price,
                'image'=>$product->image[0]
            ];
        }

    }
    self::addCartItemsToCookie($cart_items);
    return count($cart_items);
}

static public function removeCartItem($product_id){
     $cart_items=self::getCartItemsFromCookie();
    foreach ($cart_items as $key=>$item){
        if ($item['product_id']==$product_id){
            unset($cart_items[$key]);
        }
    }
    self::addCartItemsToCookie($cart_items);
    return   $cart_items;


    }
    static public function incrementQuantityToCartItem($product_id){
        $cart_items=self::getCartItemsFromCookie();
        foreach ($cart_items as $key=>$item){
            if ($item['product_id']==$product_id){
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount']=$cart_items[$key]['quantity']*$cart_items[$key]['unit_amount'];

            }
        }
        self::addCartItemsToCookie($cart_items);
          return   $cart_items;

    }


    static public function decrementQuantityToCartItem($product_id){
        $cart_items=self::getCartItemsFromCookie();
        foreach ($cart_items as $key=>$item){
            if ($item['product_id']==$product_id){
                if ($item['quantity']>1){
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount']=$cart_items[$key]['quantity']*$cart_items[$key]['unit_amount'];

                }

            }
        }
        self::addCartItemsToCookie($cart_items);
        return$cart_items;

    }

static  public function calculateGrandTotal($items){
 return array_sum(array_column($items,'total_amount'));
}




static public function addCartItemsToCookie($cart_item){
    Cookie::queue('cart_items',json_encode($cart_item),60*24*30);
}
static public function clearCarItemsFromCookie(){
    Cookie::queue(Cookie::forget('cart_items'));
}



static public function  getCartItemsFromCookie(){
    $cart_items=json_decode(Cookie::get('cart_items'),true);
    if (!$cart_items){
        $cart_items=[];
    }
    return$cart_items;
}


}
