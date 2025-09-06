<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                        'id'=>$this->id,

            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'phone' => $this->phone,
            'country' => $this->country,
            'profile_image'=>$this->getImageAttribute,
            'city' => $this->city,
            'gender' => $this->gender,
            'date_of_birth'=>$this->date_of_birth,
            'email' => $this->user?->email,
            'name'  => $this->user?->name,
'is_banned' => $this->is_banned == 1 ? 'محظور' : 'نشط',
        ];
    }
}
