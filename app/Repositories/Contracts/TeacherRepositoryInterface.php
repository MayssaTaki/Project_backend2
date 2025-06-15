<?php
namespace App\Repositories\Contracts;
use App\Models\Teacher;

interface TeacherRepositoryInterface{
    public function update(Teacher $teacher, array $data): Teacher;
    public function getAllTeachers();
    public function search(string $query);
    public function countTeachers(): int;
    public function find($id): Teacher;
    public function approve(Teacher $teacher): Teacher;
    public function reject(Teacher $teacher): Teacher;
    public function getApprovedTeachers();
    public function getRejectedTeachers();
    public function getPendingTeachers();
    public function create(array $data): Teacher;
}