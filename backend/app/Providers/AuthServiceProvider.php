<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Raza;
use App\Policies\RazaPolicy;
use App\Models\Animales;
use App\Policies\AnimalesPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Raza::class => RazaPolicy::class,
        Animales::class => AnimalesPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}