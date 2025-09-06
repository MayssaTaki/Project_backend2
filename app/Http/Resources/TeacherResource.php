<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->user_id,

            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'specialization' => $this->specialization,
            'phone' => $this->phone,
            'country' => $this->country,
            'city' => $this->city,
            'gender' => $this->gender,
        'Previous_experiences'=>$this->Previous_experiences,
            'status'=>$this->status,
            'email' => $this->user?->email,
            'name'  => $this->user?->name,
        ];
    }
}