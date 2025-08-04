<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\CourseVideo;
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
        return Course::with($relations)
                        ->where('accepted', true)
                        ->findOrFail($id);
    }

    public function findByTeacherName(string $teacherName): Collection
    {
        return Course::with(['category', 'teacher.user'])
            ->whereHas('teacher.user', function($query) use ($teacherName) {
                $query->where('first_name', 'like', "%$teacherName%")
                    ->orWhere('last_name', 'like', "%$teacherName%");
            })
            ->where('accepted', true)
            ->get();
    }

    public function findByCategoryName(string $categoryName): Collection
    {
        return Course::with(['category', 'teacher.user'])
            ->whereHas('category', function($query) use ($categoryName) {
                $query->where('name', 'like', "%$categoryName%");
            })
            ->where('accepted', true)
            ->get();
    }

    public function searchByName(string $courseName): Collection
    {
        return Course::with(['category', 'teacher.user'])
            ->where('name', 'like', "%$courseName%")
            ->where('accepted', true)
            ->get();
    }

    public function findByCategoryId(int $categoryId): Collection
    {
        return Course::with(['category', 'teacher.user'])
            ->where('category_id', $categoryId)
            ->where('accepted', true)
            ->get();
    }

    public function registerStudent(int $courseId, int $studentId): CourseRegistration
    {
        return CourseRegistration::create([
            'course_id' => $courseId,
            'student_id' => $studentId,
            'status' => 'active',
            'registered_at' => now()
        ]);
    }

    public function isStudentRegistered(int $courseId, int $studentId): bool
    {
        return CourseRegistration::where('course_id', $courseId)
                            ->where('student_id', $studentId)
                            ->exists();
    }

    public function findById(int $courseId): ?Course
    {
        return Course::find($courseId);
    }

    public function createVideo(array $videoData): CourseVideo
    {
        return CourseVideo::create($videoData);
    }

    //this method will return the videos linked to a course, ordered "first uploaded shows first"
    public function getVideosByCourseId(int $courseId): Collection
    {
        return CourseVideo::where('course_id', $courseId)
            ->orderBy('uploaded_at', 'asc')
            ->get();
    }
}
