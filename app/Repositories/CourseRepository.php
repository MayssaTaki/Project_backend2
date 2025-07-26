<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function create(array $data): Course
    {
        return Course::create($data);
    }

    public function update(int $id, array $data): Course
    {
        $course = Course::findOrFail($id);
        $course->update($data);
        return $course->fresh(); // Return fresh instance from database
    }

    public function delete(int $id): void
    {
        $course = Course::findOrFail($id);
        $course->delete();
    }

    public function findWithRelations(int $id, array $relations): Course
    {
        return Course::with($relations)->findOrFail($id);
    }

    public function findByTeacherName(string $teacherName): Collection
    {
        return Course::with(['category', 'teacher.user'])
            ->whereHas('teacher.user', function($query) use ($teacherName) {
                $query->where('first_name', 'like', "%$teacherName%")
                    ->orWhere('last_name', 'like', "%$teacherName%");
            })
            ->get();
    }

    public function findByCategoryName(string $categoryName): Collection
    {
        return Course::with(['category', 'teacher.user'])
            ->whereHas('category', function($query) use ($categoryName) {
                $query->where('name', 'like', "%$categoryName%");
            })
            ->get();
    }

    public function searchByName(string $courseName): Collection
    {
        return Course::with(['category', 'teacher.user'])
            ->where('name', 'like', "%$courseName%")
            ->get();
    }

    public function findByCategoryId(int $categoryId): Collection
    {
        return Course::with(['category', 'teacher.user'])
            ->where('category_id', $categoryId)
            ->get();
    }
}
