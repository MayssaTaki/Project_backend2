<?php
namespace App\Repositories\Contracts;
use App\Models\CourseVideo;
use App\Models\Course;
use App\Models\CourseRegistration;
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
    public function registerStudent(int $courseId, int $studentId): CourseRegistration;
    public function isStudentRegistered(int $courseId, int $studentId): bool;
    public function findById(int $courseId): ?Course;
    public function createVideo(array $videoData): CourseVideo;
    public function getVideosByCourseId(int $courseId): Collection;
    public function acceptCourse(int $courseId);
    public function rejectCourse(int $courseId);
        public function getAll();
            public function getStudentsByCourse(int $courseId);
}