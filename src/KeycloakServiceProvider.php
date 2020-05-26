<?php

namespace App\Laravel\Keycloak\Admin;

use App\Laravel\Keycloak\Admin\Builders\RoleRepresentationBuilderAdapter;
use App\Laravel\Keycloak\Admin\Builders\UserRepresentationBuilderAdapter;
use App\Laravel\Keycloak\Admin\Representations\RoleRepresentationBuilder;
use App\Laravel\Keycloak\Admin\Representations\UserRepresentationBuilder;
use Illuminate\Support\ServiceProvider;

class KeycloakServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/keycloak-admin.php' => config_path('keycloak-admin.php'),
        ], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/keycloak-admin.php', 'keycloak-admin');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('keycloak-admin.client', function ($app) {
            return new ClientManager($app);
        });
        $this->app->singleton('keycloak-admin.user-representation-builder', function ($app) {
            return new UserRepresentationBuilderAdapter(new UserRepresentationBuilder());
        });
        $this->app->singleton('keycloak-admin.role-representation-builder', function ($app) {
            return new RoleRepresentationBuilderAdapter(new RoleRepresentationBuilder());
        });
    }
}
