<?php

namespace Tests\Unit\Utils\Repositories\CompareValue;

use App\Utils\FrameworkAdapters\Container\Contracts\Container;
use App\Utils\Repositories\CompareValue\CompareValue;
use App\Utils\Repositories\CompareValue\CompareValueCollection;
use App\Utils\Repositories\CompareValue\CompareValueFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class CompareValueFactoryTest
 * @covers \App\Utils\Repositories\CompareValue\CompareValueFactory
 * @package Tests\Unit\Utils\Repositories\CompareValue
 */
class CompareValueFactoryTest extends TestCase
{
    public function testCreateCompareValueCollectionEmpty()
    {
        $containerMock = $this->createMock(Container::class);
        $collectionMock = $this->createMock(CompareValueCollection::class);
        $collectionMock
            ->expects($this->never())
            ->method('add');
        $containerMock->method('resolve')->willReturn($collectionMock);
        $factory = new CompareValueFactory($containerMock);

        $collection = $factory->createCompareValueCollection();

        $this->assertEquals(0, $collection->count());
    }

    public function testCreateCompareValueCollectionWithObjects()
    {
        $containerMock = $this->createMock(Container::class);
        $collectionMock = $this->createMock(CompareValueCollection::class);
        $collectionMock
            ->expects($this->exactly(3))
            ->method('add');
        $containerMock->method('resolve')->willReturn($collectionMock);
        $factory = new CompareValueFactory($containerMock);

        $collection = $factory->createCompareValueCollection([
            $this->createMock(CompareValue::class),
            $this->createMock(CompareValue::class),
            $this->createMock(CompareValue::class)
        ]);

        $this->assertEquals(0, $collection->count());
    }

    public function testNegativeCreateCompareValueCollectionWithObjects()
    {
        $this->expectException(\TypeError::class);

        $containerMock = $this->createMock(Container::class);
        $collectionMock = $this->createMock(CompareValueCollection::class);
        $containerMock
            ->method('resolve')
            ->willReturn($collectionMock);
        $factory = new CompareValueFactory($containerMock);

        $factory->createCompareValueCollection([
            new \stdClass()
        ]);
    }

    public function testCreateCompareValue()
    {
        $containerMock = $this->createMock(Container::class);
        $factory = new CompareValueFactory($containerMock);

        $compareValue = $factory->createCompareValue('somevalue', 'LIKE');

        $this->assertEquals('somevalue', $compareValue->getValue());
        $this->assertEquals('LIKE', $compareValue->getOperator());
    }
}
