<?php

namespace App\Providers;

use App\MailjetClass;
use Illuminate\Support\ServiceProvider;

class MailjetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(\App\MailjetClass::class, function ($app) {
            // Al ponerle (string) forzamos a que si llega un null, lo convierta en texto vacío (""). 
            // Así es IMPOSIBLE que la clase truene por el tipo de dato.
            return new \App\MailjetClass(
                (string) config('services.brevo.api_key'),
                (string) config('services.brevo.baseEmail'),
                (string) config('services.brevo.baseName')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
