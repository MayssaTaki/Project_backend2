<?php

namespace App\Providers;
use App\Repositories\UserRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\StudentRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CourseRepository;
use App\Repositories\ExamRepository;

use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WalletRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\ExamRepositoryInterface;

use App\Services\CourseService;
use App\Services\StudentService;
use App\Services\TeacherService;
use App\Services\CategoryService;
use App\Services\ExamService;

use App\Services\Interfaces\CourseServiceInterface;
use App\Services\Interfaces\StudentServiceInterface;
use App\Services\Interfaces\TeacherServiceInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\Interfaces\ExamServiceInterface;


use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(StudentServiceInterface::class, StudentService::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(TeacherServiceInterface::class, TeacherService::class);
          $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(ExamServiceInterface::class, ExamService::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CourseServiceInterface::class, CourseService::class);
        $this->app->bind(\App\Services\TransactionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin-actions', function ($user) {
            return $user->role === 'admin';
        });
    }
}
