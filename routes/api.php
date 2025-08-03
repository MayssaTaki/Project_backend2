<?php
use App\Http\Controllers\AuthController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CourseController;




Route::middleware(['reject.banned'])->group(function () {
    Route::get('/students/{id}/status', [StudentController::class, 'checkStatus']);
});

Route::middleware(['reject.banned'])->group(function () {
    Route::post('/students/{id}/ban', [StudentController::class, 'ban']);
    Route::post('/students/{id}/unban', [StudentController::class, 'unban']);
});




Route::get('/count/approved', [TeacherController::class, 'approved']);
Route::get('/count/rejected', [TeacherController::class, 'rejected']);
Route::get('/count/pened', [TeacherController::class, 'pened']);

Route::get('/count/block', [StudentController::class, 'block']);
Route::get('/count/active', [StudentController::class, 'active']);

Route::get('/categories/count', [CategoryController::class, 'countCategories']);
Route::get('/categories', [CategoryController::class, 'getAll']);
Route::get('/categories/search', [CategoryController::class, 'search']);

Route::get('/teachers/count', [TeacherController::class, 'countTeachers']);
Route::get('/students/count', [StudentController::class, 'countStudents']);

Route::get('/teachers', [TeacherController::class, 'getAllTeachers']);
Route::get('/students', [StudentController::class, 'getAllStudents']);
Route::post('/teacher/password/reset/request', [AuthController::class, 'sendResetCode']);
Route::post('/teacher/password/reset', [AuthController::class, 'resetPassword']);
Route::get('/teachers/search', [TeacherController::class, 'search']);
Route::get('/students/search', [StudentController::class, 'search']);
Route::put('/students/{student}', [StudentController::class, 'update']);
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');


Route::post('/teacher/register', [TeacherController::class, 'register']);
Route::post('/student/register', [StudentController::class, 'register']);
Route::put('/teachers/{teacher}', [TeacherController::class, 'update']);

Route::middleware([ 'reject.banned'])->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/courses/register', [CourseController::class, 'registerForCourse']);

});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refreshToken']);



Route::middleware(['reject.banned'])->prefix('wallet')->group(function () {
    Route::post('/student/charge', [WalletController::class, 'studentCharge']);
    Route::get('/student/balance', [WalletController::class, 'studentBalance']);
});
Route::get('/teacher/balance', [WalletController::class, 'teacherBalance']);

Route::middleware(['teacher'])->group(function () {
    Route::post('/courses/add', [CourseController::class, 'addCourse']);
    Route::post('/courses/update', [CourseController::class, 'updateCourse']);

});

Route::put('/users/{id}/profile', [UserController::class, 'updateUserProfile']);
Route::get('/courses/{courseId}', [CourseController::class, 'getCourseDetails']);
Route::delete('/courses/{courseId}', [CourseController::class, 'deleteCourse']);
Route::post('/courses/byTeacher', [CourseController::class, 'getCoursesByTeacherName']);
Route::post('/courses/byCategory', [CourseController::class, 'getCoursesByCategoryName']);
Route::post('/courses/byName', [CourseController::class, 'searchCoursesByName']);
Route::get('/courses/byCategory/{categoryId}', [CourseController::class, 'getCoursesByCategoryId']);
