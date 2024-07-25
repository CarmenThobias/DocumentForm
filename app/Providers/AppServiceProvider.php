<?php

namespace App\Providers;
use App\Models\Document;
use App\Policies\DocumentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    protected $policies = [
        Document::class => DocumentPolicy::class,
    ];

    public function boot()
{
    Paginator::defaultView('vendor.pagination.custom');

    $this->registerPolicies();

    Gate::define('uploadDocuments', function ($user) {
        $role = session('role'); // Get the role from the session
        return $role === 'Admin' || $role === 'Secretary';
    });

    Gate::define('deleteDocuments', function ($user) {
        $role = session('role'); // Get the role from the session
        return $role === 'Admin';
    });
}

}
