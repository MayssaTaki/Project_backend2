<?php
namespace App\Repositories\Contracts;
use App\Models\Course;


interface CourseRepositoryInterface{
    public function create(array $data): Course;
    public function update(int $id, array $data): Course;
    public function delete(int $id): void;
}