<?php

namespace App\Providers;

use App\Models\PasswordResetToken;
use App\Repositories\BaseRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\RepositoryInterface;
use App\Repositories\Roles\RoleRepository;
use App\Repositories\Users\UserRepository;
use App\Observers\PasswordResetTokenObserver;
use App\Repositories\Products\ProductRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Roles\RoleRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Departments\DepartmentRepository;
use App\Repositories\Permissions\PermissionRepository;
use App\Repositories\Products\ProductRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Departments\DepartmentRepositoryInterface;
use App\Repositories\Permissions\PermissionRepositoryInterface;
use App\Repositories\CategoryAttribute\CategoryAttributeRepository;
use App\Repositories\CategoryAttribute\CategoryAttributeRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        // Manually
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CategoryAttributeRepositoryInterface::class, CategoryAttributeRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(RepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PasswordResetToken::observe(PasswordResetTokenObserver::class);
    }
}
