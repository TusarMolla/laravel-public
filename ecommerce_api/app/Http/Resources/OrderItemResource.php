<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "quantity"=>$this->quantity,
            "item_price"=>$this->price,
            "item_details"=>new ProductMiniResource(Product::where("id",$this->product_id)->first()),
        ];
    }
}
