<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $prodcuts = Product::all();
        return $prodcuts;

    }

    public function store(Request $request){
        $product = new Product();

        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->short_des = $request->short_des;
        $product->description = $request->description;
        $product->image = $request->image;

        $product->save();

        return $this->success("Product added");


    }
    public function edit($id){

    }
    public function update($id){

    }
    public function deistroy($id){
        
    }
}

