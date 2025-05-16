<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TeacherController;

use Illuminate\Support\Facades\Route;


Route::get('/', [AdminController::class, 'count'])->name('welcome');

Route::get('/categories', [AdminController::class, 'index'])->name('categories.index');
Route::get('/categories/search', [AdminController::class, 'search'])->name('categories.search');
Route::post('/categories', [AdminController::class, 'store'])->name('categories.store');
Route::put('/categories/{category}', [AdminController::class, 'update'])->name('categories.update');


Route::get('/teachers', [AdminController::class, 'getAllTeachers'])->name('teachers.index');
Route::put('/teachers/{id}/approve', [AdminController::class, 'approve'])->name('teachers.approve');
Route::put('/teachers/{id}/reject', [AdminController::class, 'reject'])->name('teachers.reject');
Route::get('/teachers/search', [AdminController::class, 'searchTeacher'])->name('teachers.search');

Route::get('/students', [AdminController::class, 'getAllStudents'])->name('students.index');
Route::get('/students/search', [AdminController::class, 'searchStudent'])->name('students.search');
 Route::put('/students/{id}/ban', [AdminController::class, 'ban'])->name('students.block');
    Route::put('/students/{id}/unban', [AdminController::class, 'unban'])->name('students.unblock');
