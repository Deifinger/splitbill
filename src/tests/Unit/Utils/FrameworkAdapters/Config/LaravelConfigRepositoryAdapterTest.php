<?php

namespace Tests\Unit\Utils\FrameworkAdapters\Config;

use App\Utils\FrameworkAdapters\Config\LaravelConfigRepositoryAdapter;
use Illuminate\Contracts\Config\Repository;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigRepositoryTest
 * @covers LaravelConfigRepositoryAdapter
 * @package Tests\Unit\Utils\FrameworkAdapters\Config
 */
class LaravelConfigRepositoryAdapterTest extends TestCase
{
    private function createConfigRepository(Repository $configRepositoryMock): LaravelConfigRepositoryAdapter
    {
        return new LaravelConfigRepositoryAdapter($configRepositoryMock);
    }

    private function methodHas(bool $mockReturns)
    {
        $mock = $this->createMock(Repository::class);
        $mock->method('has')
            ->willReturn($mockReturns);
        $configRepo = $this->createConfigRepository($mock);

        $result = $configRepo->has('anykey');

        $this->assertEquals($mockReturns, $result);
    }

    public function testPositiveHas()
    {
        $this->methodHas(true);
    }

    public function testNegativeHas()
    {
        $this->methodHas(false);
    }

    /**
     * @dataProvider allDataProvider
     * @param array $expectedResult
     */
    public function testAll(array $expectedResult)
    {
        $mock = $this->createMock(Repository::class);
        $mock->method('all')
            ->willReturn($expectedResult);
        $configRepo = $this->createConfigRepository($mock);

        $actualResult = $configRepo->all();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function allDataProvider()
    {
        return [
            [[1 => 'abc']],
            [['2' => [], 'abc' => new \stdClass()]],
            [['key' => ['key' => ['key' => null]]]]
        ];
    }

    /**
     * @dataProvider prependDataProvider
     */
    public function testPrepend($set, $key, $value, $expect)
    {
        $mock = $this->createMock(Repository::class);
        $mock->method('get')
            ->willReturn($expect);
        $configRepo = $this->createConfigRepository($mock);
        $configRepo->set($key, $set);

        $configRepo->prepend($key, $value);
        $actualResult = $configRepo->get($key);

        $this->assertEquals($expect, $actualResult);
    }

    public function prependDataProvider()
    {
        return [
            [
                'set' => ['abc' => true],
                'key' => 'somekey',
                'value' => 'somevalue',
                'expect' => [0 => 'somevalue', 'abc' => true],
            ],
            [
                'set' => null,
                'key' => 'somekey',
                'value' => 'somevalue',
                'expect' => null
            ],
            [
                'set' => [0 => true],
                'key' => 'somekey',
                'value' => 'somevalue',
                'expect' => [0 => 'somevalue', 1 => true]
            ],
        ];
    }

    /**
     * @dataProvider pushDataProvider
     */
    public function testPush($set, $key, $value, $expect)
    {
        $mock = $this->createMock(Repository::class);
        $mock->method('get')
            ->willReturn($expect);
        $configRepo = $this->createConfigRepository($mock);
        $configRepo->set($key, $set);

        $configRepo->push($key, $value);
        $actualResult = $configRepo->get($key);

        $this->assertEquals($expect, $actualResult);
    }

    public function pushDataProvider()
    {
        return [
            [
                'set' => ['abc' => true],
                'key' => 'somekey',
                'value' => 'somevalue',
                'expect' => [0 => 'somevalue', 'abc' => true],
            ],
            [
                'set' => null,
                'key' => 'somekey',
                'value' => 'somevalue',
                'expect' => null
            ],
            [
                'set' => [0 => true],
                'key' => 'somekey',
                'value' => 'somevalue',
                'expect' => [0 => true, 0 => 'somevalue']
            ],
        ];
    }

    /**
     * @dataProvider getDataProvider
     */
    public function testGet($array, $key)
    {
        $mock = $this->createMock(Repository::class);
        $mock->method('get')
            ->with($key)
            ->willReturn($array);
        $configRepo = $this->createConfigRepository($mock);
        $configRepo->set($key, $array);

        $actualResult = $configRepo->get($key);

        $this->assertEquals($array, $actualResult);
    }

    public function getDataProvider()
    {
        return [
            [
                'array' => ['abc' => true],
                'key' => 'somekey',
            ],
            [
                'array' => null,
                'key' => 'somekey',
            ],
            [
                'array' => [0 => true],
                'key' => 'somekey',
            ],
        ];
    }

    private function setTest1(LaravelConfigRepositoryAdapter $configRepo, $key, $firstState)
    {
        $configRepo->set($key, $firstState);

        $intermediateResult = $configRepo->get($key);

        $this->assertEquals($firstState, $intermediateResult);
    }

    private function setTest2(LaravelConfigRepositoryAdapter $configRepo, $key, $index, $value, $expect)
    {
        $configRepo->set("$key.$index", $value);

        $actualResult = $configRepo->get($key);

        $this->assertEquals($expect, $actualResult);
    }

    /**
     * @dataProvider setDataProvider
     */
    public function testSet($key, $firstState, $index, $value, $expect)
    {
        // Test with intermediate result
        $mock = $this->createMock(Repository::class);
        $mock->method('get')
            ->will($this->onConsecutiveCalls($firstState, $expect));
        $configRepo = $this->createConfigRepository($mock);
        $this->setTest1($configRepo, $key, $firstState);
        $this->setTest2($configRepo, $key, $index, $value, $expect);
    }

    public function setDataProvider()
    {
        return [
            [
                'key' => 'somekey',
                'firstState' => ['abc' => true],
                'index' => '0',
                'value' => 'somevalue',
                'expect' => [0 => 'somevalue', 'abc' => true],
            ],
            [
                'key' => 'somekey',
                'firstState' => null,
                'index' => '',
                'value' => 'somevalue',
                'expect' => 'somevalue'
            ],
            [
                'key' => 'somekey',
                'firstState' => [0 => true],
                'index' => 'newkey',
                'value' => 'somevalue',
                'expect' => [0 => true, 'newkey' => 'somevalue']
            ],
        ];
    }
}
