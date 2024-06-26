<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(){

        $user_id = auth()->user()->id;
        $carts = Cart::where("user_id",$user_id)->get(); 
        return CartResource::collection($carts);
    }

    public function addToCart(Request $request){
        $user_id = auth()->user()->id;
        $cart = Cart::where("user_id",$user_id)->where("product_id",$request->product_id)->first();
        if(!$cart){
            $cart = new Cart();
            $cart->quantity = 0;
        }
        $cart->product_id = $request->product_id;
        $cart->quantity += $request->qunatity;
        $cart->user_id = $user_id;

        $product = Product::where("id",$cart->product_id)->first();
        if($product->quantity<$cart->quantity){
            return $this->failed("Stock is not available");
        }

        $cart->save();
        return $this->success("Cart has been successfully added.");
        
    }

    public function deleteCart($id){
        $user_id = auth()->user()->id;
        $cart = Cart::where("user_id",$user_id)->where("id",$id)->first();
        if(!$cart){ 
            return $this->failed("Failed to remove cart");
        }

        $cart->save();
        return $this->success("Cart has been successfully deleted.");
        
    }

}
