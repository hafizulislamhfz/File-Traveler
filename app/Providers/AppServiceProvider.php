<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
    public function boot(): void
    {
        ////custom validation
        Validator::extend('total_size', function ($attribute, $value, $parameters, $validator) {
            $maxTotalSizeInMB = (int) $parameters[0];
            $maxTotalSizeInBytes = $maxTotalSizeInMB * 1048576; // Convert MB to bytes
            $totalSize = 0;

            foreach ($value as $file) {
                $totalSize += $file->getSize();
            }

            return $totalSize <= $maxTotalSizeInBytes;
        });
    }
}
