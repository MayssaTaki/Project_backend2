<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Models\TeachersEvaluation;
use App\Models\User;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Exceptions\TeacherRegistrationException;

class TeacherRepository implements TeacherRepositoryInterface

{
    public function create(array $data): Teacher
    {
        return Teacher::create($data);
    }

 public function update(Teacher $teacher, array $data): Teacher
{
    $teacher->update($data);
    return $teacher->fresh(); 
}


public function getAllTeachers()
{
    return Teacher::with('user') 
        ->whereHas('user', function($query) {
            $query->where('role', 'teacher');
        })->paginate(10);
}

public function search(string $query)
    {
        return Teacher::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('specialization', 'LIKE', "%{$query}%")
            ->paginate(10);
    }

    public function countTeachers(): int
    {
        return Teacher::count();
    }

    public function find($id): Teacher
    {
        return Teacher::findOrFail($id);
    }

    public function approve(Teacher $teacher): Teacher
    {
        $teacher->update(['status' => 'approved']);
        return $teacher;
    }

    public function reject(Teacher $teacher): Teacher
    {
        $teacher->update(['status' => 'rejected']);
        return $teacher;
    }

    public function getApprovedTeachers()
    {
        return Teacher::where('status', 'approved')->get();
    }

    public function getRejectedTeachers()
    {
        return Teacher::where('status', 'rejected')->get();
    }

    public function getPendingTeachers()
    {
        return Teacher::where('status', 'pending')->get();
    }

    public function createEvaluation(array $evaluationData): TeachersEvaluation
    {
        return TeachersEvaluation::create($evaluationData);
    }

    public function updateEvaluation(int $evaluationId, int $value): TeachersEvaluation
    {
        $evaluation = TeachersEvaluation::findOrFail($evaluationId);
        $evaluation->update([
            'evaluation_value' => $value
        ]);
        return $evaluation->fresh();
    }

    public function getStudentEvaluation(int $teacherId, int $studentId): ?TeachersEvaluation
    {
        return TeachersEvaluation::where('teacher_id', $teacherId)
            ->where('student_id', $studentId)
            ->first();
    }

    public function getTeacherAverageRating(int $teacherId): float
    {
        return TeachersEvaluation::where('teacher_id', $teacherId)
            ->avg('evaluation_value') ?? 0;
    }
}
