<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'question_id' => $this->id,
            'text' => $this->question_text,
             'choices' => ChoiceResource::collection($this->whenLoaded('choices')),
        ];
    }
}
