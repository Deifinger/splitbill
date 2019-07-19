<?php

namespace App\Utils\FrameworkAdapters\Container\Contracts;

/**
 * Interface Container
 * @package Http\Adapter\Container\Contracts
 */
interface Container
{
    /**
     * @param string $className
     * @return mixed
     */
    public function resolve(string $className);
}
