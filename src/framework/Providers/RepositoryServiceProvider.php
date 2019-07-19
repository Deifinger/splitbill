<?php

namespace Framework\Providers;

use App\Utils\Repositories\CompareValue\CompareValueFactory;
use App\Utils\Repositories\Contracts\CompareValueFactory as CompareValueFactoryInterface;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        CompareValueFactoryInterface::class             => CompareValueFactory::class,
    ];
}
