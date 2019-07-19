<?php


namespace App\Utils\Repositories\CompareValue;


use App\Utils\FrameworkAdapters\Container\Contracts\Container;

class CompareValueFactory implements \App\Utils\Repositories\Contracts\CompareValueFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * CompareValueFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $value
     * @param string $operator
     * @return CompareValue
     */
    public function createCompareValue($value, string $operator = '='): CompareValue
    {
        return new CompareValue($value, $operator);
    }

    /**
     * @param array $objects
     * @return CompareValueCollection
     */
    public function createCompareValueCollection(array $objects = []): CompareValueCollection
    {
        /** @var CompareValueCollection $collection */
        $collection = $this->container->resolve(CompareValueCollection::class);
        foreach ($objects as $object) {
            $collection->add($object);
        }
        return $collection;
    }
}
