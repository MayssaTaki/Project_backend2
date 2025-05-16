<?php

namespace App\Providers;
use App\Repositories\UserRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\StudentRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WalletRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;





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
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);

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
