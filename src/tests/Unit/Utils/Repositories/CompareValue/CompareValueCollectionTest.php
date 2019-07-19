<?php

namespace Tests\Unit\Utils\Repositories\CompareValue;

use App\Utils\Repositories\CompareValue\CompareValue;
use App\Utils\Repositories\CompareValue\CompareValueCollection;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Class CompareValueCollectionTest
 * @covers \App\Utils\Repositories\CompareValue\CompareValueCollection
 * @package Tests\Unit\Utils\Repositories\CompareValue
 */
class CompareValueCollectionTest extends TestCase
{
    private function dummyCompareValue()
    {
        return $this->createMock(CompareValue::class);
    }

    private function compareValueCollectionWithMockedCount(int $countShouldReturn): CompareValueCollection
    {
        $mock = $this->createMock(Collection::class);
        $mock->method('count')->willReturn($countShouldReturn);
        return new CompareValueCollection($mock);
    }

    public function testAdd()
    {
        $collection = $this->compareValueCollectionWithMockedCount(1);

        $collection->add($this->dummyCompareValue());

        $this->assertEquals(1, $collection->count());
    }

    public function testCountEmpty()
    {
        $collection = $this->compareValueCollectionWithMockedCount(0);

        $this->assertEquals(0, $collection->count());
    }

    public function testCountAddedThree()
    {
        $collection = $this->compareValueCollectionWithMockedCount(3);

        $collection->add($this->dummyCompareValue());
        $collection->add($this->dummyCompareValue());
        $collection->add($this->dummyCompareValue());

        $this->assertEquals(3, $collection->count());
    }

    public function testGetIterator()
    {
        $collectionMock = $this->createMock(Collection::class);
        $arrayIteratorMock = $this->createMock(\ArrayIterator::class);
        $collectionMock->method('getIterator')->willReturn($arrayIteratorMock);
        $collection = new CompareValueCollection($collectionMock);

        $iterator = $collection->getIterator();

        $this->assertInstanceOf(\ArrayIterator::class, $iterator);
    }
}
