<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
           'choice_id' => $this->id,
            'text' => $this->choice_text,
            'is_correct' => (bool) $this->is_correct,
        ];
    }
}
