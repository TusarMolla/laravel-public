<?php

namespace App\Http\Resources;

use App\Models\OrderItem;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "id"=>$this->id,
            "payment_status"=>$this->id,
            "order_status"=>$this->id,
            "price"=>$this->id,
            "items"=>OrderItemResource::collection(OrderItem::where("order_id",$this->id)->get()),
        ];
    }
}
