<?php

namespace App\Providers;
use App\Repositories\UserRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\StudentRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Gate;





use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository();
        });
        $this->app->bind(TeacherRepository::class, function ($app) {
            return new TeacherRepository();
        });
        $this->app->bind(StudentRepository::class, function ($app) {
            return new StudentRepository();
        });
        $this->app->bind(WalletRepository::class, function ($app) {
            return new WalletRepository();
        });
        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository();
        });
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
