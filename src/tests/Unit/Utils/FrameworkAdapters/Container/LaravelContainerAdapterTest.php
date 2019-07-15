<?php


namespace Tests\Unit\Utils\FrameworkAdapters\Container;


use App\Utils\FrameworkAdapters\Container\LaravelContainerAdapter;
use ReflectionException;
use Tests\Support\TestCase;

class LaravelContainerAdapterTest extends TestCase
{
    /**
     * @var LaravelContainerAdapter
     */
    private $container;

    protected function setUp(): void
    {
        $this->container = new LaravelContainerAdapter();
    }

    public function testPositiveResolve()
    {
        $class = $this->container->resolve(LaravelContainerAdapter::class);

        $this->assertInstanceOf(LaravelContainerAdapter::class, $class);
    }

    public function testNegativeResolve()
    {
        $this->expectException(ReflectionException::class);

        $this->container->resolve('SomeWrongClass');
    }

}