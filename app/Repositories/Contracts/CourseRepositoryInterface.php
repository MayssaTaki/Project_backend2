<?php
namespace App\Repositories\Contracts;
use App\Models\Course;


interface CourseRepositoryInterface{
 public function create(array $data): Course;
}