<?php

/*
 * This file is part of the wangyanlong/weather.
 *
 * (c) wangyanlong <>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Wangyanlong\Weather;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Weather::class, function () {
            return new Weather(config('services.weather.key'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    public function provides()
    {
        return [Weather::class, 'weather'];
    }

}
