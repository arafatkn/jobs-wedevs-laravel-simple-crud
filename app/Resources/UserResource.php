<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $return = ['id','name','email'];

        foreach($return as $key)
            $data[$key] = $this->$key;

        $data["gravatar"] = md5(trim($this->email));

        return $data;
    }
}
