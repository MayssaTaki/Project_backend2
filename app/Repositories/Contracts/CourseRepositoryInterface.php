<?php
namespace App\Repositories\Contracts;
use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface{
    public function create(array $data): Course;
    public function update(int $id, array $data): Course;
    public function delete(int $id): void;
    public function findWithRelations(int $id, array $relations): Course;
    public function findByTeacherName(string $teacherName): Collection;
    public function findByCategoryName(string $categoryName): Collection;
    public function searchByName(string $courseName): Collection;
    public function findByCategoryId(int $categoryId): Collection;
}