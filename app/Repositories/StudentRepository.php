<?php

namespace App\Repositories;

use App\Models\Student;
use App\Models\User;
use App\Repositories\Contracts\StudentRepositoryInterface;

class StudentRepository implements StudentRepositoryInterface

{
    public function create(array $data): Student
    {
        return Student::create($data);
    }

    public function getAllStudents()
{
    return Student::with('user') 
        ->whereHas('user', function($query) {
            $query->where('role', 'student');
        })->paginate(10);
}

public function search(string $query)
    {
        return Student::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->paginate(10);
    }

    public function update(Student $student, array $data): Student
    {
        $student->update($data);
        return $student;
    }

    public function countStudents(): int
    {
        return Student::count();
    }

    public function find($id): Student
    {
        return Student::findOrFail($id);
    }

    public function ban(Student $student): void
    {
        $student->update(['is_banned' => true]);
    }

    public function unban(Student $student): void
    {
        $student->update(['is_banned' => false]);
    }

    public function isBanned(Student $student): bool
    {
        return $student->is_banned;
    }

    public function getBanStudents()
    {
        return Student::where('is_banned', '1')->get();
    }

    public function getActiveStudents()
    {
        return Student::where('is_banned', '0')->get();
    }
}
