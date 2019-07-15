<?php

namespace Framework\Providers;

use App\Utils\FrameworkAdapters\Container\Contracts\Container;
use App\Utils\FrameworkAdapters\Container\LaravelContainerAdapter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class FrameworkAdapterServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        Container::class => LaravelContainerAdapter::class,
    ];
}
