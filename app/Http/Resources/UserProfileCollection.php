<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
// use Illuminate\Http\Resources\Json\Resource;

class UserProfileCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // echo 'dfd';die;
        return [
            'user_id'       => $this->id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'email'         => $this->email,
            'mobile'        => $this->mobile,
            'address'       => $this->address,            
            'profile_pic'   => !empty($this->profile_pic) ? asset('storage/app/public/user_images/' . $this->profile_pic) : asset('storage/app/public/default/default.jpg'),
        ];
    }
}
