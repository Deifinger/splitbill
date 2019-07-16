<?php


namespace App\Utils\Repositories\CompareValue;


use Illuminate\Support\Collection;

class CompareValueCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * CompareValueCollection constructor.
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param CompareValue $compareValue
     * @return $this
     */
    public function add(CompareValue $compareValue)
    {
        $this->collection->add($compareValue);
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return $this->collection->getIterator();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->collection->count();
    }
}
