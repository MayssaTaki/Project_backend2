<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'course_id'=>$this->course_id,
            'exam_id' => $this->id,
            
            'duration_minutes' => $this->duration_minutes,
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
