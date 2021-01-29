<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $return = ['id','title','price','description','created_at','updated_at'];

        foreach($return as $key)
            $data[$key] = $this->$key;

        $data["image"] = $this->image_url;

        return $data;
    }
}
