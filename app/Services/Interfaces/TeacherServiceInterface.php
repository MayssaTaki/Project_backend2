<?php

namespace App\Services\Interfaces;
use App\Models\Teacher;

interface TeacherServiceInterface {
    public function register(array $data): Teacher;
    public function update(Teacher $teacher, array $data): Teacher;
    public function getAllTeachers();
    public function search(string $query);
    public function countTeachers(): int;
    public function approveTeacher($id);
    public function rejectTeacher($id);
    public function getApprovedTeachers();
    public function getRejectedTeachers();
    public function getPendingTeachers();
    public function evaluateTeacher(int $teacherId, int $studentId, int $evaluationValue);
    public function getTeacherAverageRating(int $teacherId): array;
}