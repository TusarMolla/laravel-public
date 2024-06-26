<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $orders = Order::where("user_id", $user_id)->get();
        return OrderResource::collection($orders);
    }

    public function store()
    {
        $user_id = auth()->user()->id;
        $order = new Order();
        $carts = Cart::where("user_id", $user_id)->get();
        if (!$carts) {
            return $this->failed("Cart is empty");
        }
        $tmp_items = null;
        $i = 0;
        foreach ($carts as $item) {
            $product = Product::where("id", $item->product_id)->first();
            if ($product->quantity < $item->quantity) {
                continue;
            }
            $tmp[$i]['product_id'] = $product->id;
            $tmp[$i]['quantity'] = $item->quantity;
            $tmp[$i]['price'] = $item->quantity * $product->price;
            $i++;
        }

        $carts->delete();
        if (!$tmp_items) {
            return $this->failed("Cart is empty");
        }

        $order->user_id = $user_id;
        $order->payment_method = "cash on delivery";
        $order->payment_status = "unpaid";
        $order->delivery_status = "pending";
        $order->save();

        foreach ($tmp_items as $item) {
            $order_item = new OrderItem();
            $order_item->product_id = $item->product_id;
            $order_item->quantity = $order->quantity;
            $order_item->price = $order->price;
            $order_item->save();
        }

        return $this->success("Order created");
    }


    public function show() {
        $user_id = auth()->user()->id;
        $order = Order::where("user_id", $user_id)->first();
        return new OrderResource($order);
    }
}
