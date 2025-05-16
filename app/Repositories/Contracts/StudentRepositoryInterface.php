<?php
namespace App\Repositories\Contracts;
use App\Models\Student;

interface StudentRepositoryInterface{
    public function create(array $data): Student;
    public function getAllStudents();
    public function search(string $query);
    public function update(Student $student, array $data): Student;
    public function countStudents(): int;
    public function find($id): Student;
    public function ban(Student $student): void;
    public function unban(Student $student): void;
    public function isBanned(Student $student): bool;
    public function getBanStudents();
    public function getActiveStudents();
}