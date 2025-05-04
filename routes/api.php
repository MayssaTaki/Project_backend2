<?php
use App\Http\Controllers\AuthController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WalletController;




Route::middleware([ 'reject.banned'])->group(function () {
    Route::get('/students/{id}/status', [StudentController::class, 'checkStatus']);
});

Route::middleware(['reject.banned'])->group(function () {
    Route::post('/students/{id}/ban', [StudentController::class, 'ban']);
    Route::post('/students/{id}/unban', [StudentController::class, 'unban']);
});


Route::middleware([ 'can:admin-actions'])->prefix('admin')->group(function () {
    Route::post('/teachers/{id}/approve', [TeacherController::class, 'approve']);
    Route::post('/teachers/{id}/reject', [TeacherController::class, 'reject']);
});


Route::get('/count/approved', [TeacherController::class, 'approved']);
Route::get('/count/rejected', [TeacherController::class, 'rejected']);
Route::get('/count/pened', [TeacherController::class, 'pened']);

Route::get('/count/block', [StudentController::class, 'block']);
Route::get('/count/active', [StudentController::class, 'active']);




Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/count', [CategoryController::class, 'countCategories']);
Route::get('/categories', [CategoryController::class, 'getAll']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
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

Route::post('/login', [AuthController::class, 'login']);});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refreshToken'])->middleware('auth:api');



Route::middleware(['reject.banned'])->prefix('wallet')->group(function () {
    Route::post('/student/charge', [WalletController::class, 'studentCharge']);
    Route::get('/student/balance', [WalletController::class, 'studentBalance']);

});
Route::get('/teacher/balance', [WalletController::class, 'teacherBalance']);


Route::get('/users', [UserController::class, 'getAllUsers']);
Route::get('/employees', [UserController::class, 'getAllEmployees']);
Route::get('/trainers', [UserController::class, 'getAllTrainers']);
Route::get('/drivers', [UserController::class, 'getAllDrivers']);

Route::get('/search-users', [UserController::class, 'search']);
Route::get('/search-employees', [UserController::class, 'searchEmployee']);
Route::get('/search-trainers', [UserController::class, 'searchTrainer']);
Route::get('/search-drivers', [UserController::class, 'searchDriver']);

Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
Route::delete('/drivers/{id}', [UserController::class, 'deleteDriver']);
Route::delete('/trainers/{id}', [UserController::class, 'deleteTrainer']);
Route::delete('/employees/{id}', [UserController::class, 'deleteEmployee']);

Route::get('/user-counts', [UserController::class, 'getUserCounts']);
Route::get('/employee-counts', [UserController::class, 'getEmployeeCounts']);
Route::get('/trainer-counts', [UserController::class, 'getTrainerCounts']);
Route::get('/driver-counts', [UserController::class, 'getDriverCounts']);

Route::put('/users/{id}/profile', [UserController::class, 'updateUserProfile']);
