<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        collect(config('bindings.repositories'))->each(function (string $implementation, string $contract) {
            $this->app->bind($contract, $implementation);
        });
    }
}
