<?php

namespace App\Services\Interfaces;


interface CourseServiceInterface 
{
    public function createCourse(array $data);
    public function updateCourse(array $data);
    public function deleteCourse(int $courseId);
    public function getCourseDetails(int $courseId);
    public function getCoursesByTeacherName(string $teacherName);
    public function getCoursesByCategoryName(string $categoryName);
    public function searchCoursesByName(string $courseName);
    public function getCoursesByCategoryId(int $categoryId);
    public function registerStudentForCourse(int $courseId, int $studentId);
}