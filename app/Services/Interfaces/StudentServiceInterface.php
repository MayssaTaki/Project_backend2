<?php

namespace App\Services\Interfaces;
use App\Models\Student;

interface StudentServiceInterface {
public function register(array $data): Student;
public function getAllStudent();
public function search(string $query);
public function update(Student $student, array $data): Student;
public function countStudents(): int;
public function ban($id): Student;
public function unban($id): Student;
public function checkIfBanned($id): bool;
public function getActiveStudents();
public function getBanStudents();


}