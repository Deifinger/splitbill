<?php

namespace App\Utils\FrameworkAdapters\Container;


use App\Utils\FrameworkAdapters\Container\Contracts\Container;

/**
 * Class LaravelContainerAdapter
 * @package App\Utils\Adapter\Container
 */
final class LaravelContainerAdapter implements Container
{
    /**
     * @param string $className
     * @return mixed
     */
    public function resolve(string $className)
    {
        return resolve($className);
    }
}
