<?php

namespace App\Http\Resources;

use App\Http\Resources\StockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return array_merge(parent::toArray($request), [
        //     'stocks' => $this->stocks->map(function ($stock) {
        //         return $stock->symbol;
        //     }),
        // ]);
        return array_merge(parent::toArray($request), [
            'stocks' => StockResource::collection($this->stocks),
        ]);
    }
}
