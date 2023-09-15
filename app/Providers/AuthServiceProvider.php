<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models;
use App\Policies;
use Spatie\Permission\Models as PermissionModels;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Models\Produk::class => Policies\ProdukPolicy::class,
        Models\Satuan::class => Policies\SatuanPolicy::class,
        Models\Kategori::class => Policies\KategoriPolicy::class,
        Models\Lokasi::class => Policies\LokasiPolicy::class,
        Models\User::class => Policies\UserPolicy::class,
        PermissionModels\Role::class => Policies\RolePolicy::class,
        PermissionModels\Permission::class => Policies\PermissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
