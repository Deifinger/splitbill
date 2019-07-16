<?php declare(strict_types=1);

namespace Tests\Unit\Utils\Repositories\CompareValue;

use App\Utils\Repositories\CompareValue\CompareValue;
use PHPUnit\Framework\TestCase;

/**
 * Class CompareValueTest
 * @covers \App\Utils\Repositories\CompareValue\CompareValue
 * @package Tests\Unit\Utils\Repositories\CompareValue
 */
class CompareValueTest extends TestCase
{
    /**
     * @dataProvider positiveGetValueDataProvider
     */
    public function testPositiveGetValue($expectedValue)
    {
        $object = new CompareValue($expectedValue);

        $value = $object->getValue();

        $this->assertEquals($expectedValue, $value);
    }

    public function positiveGetValueDataProvider(): array
    {
        return [
            ['somevalue'],
            [-1],
            [null]
        ];
    }

    public function testNegativeGetValue()
    {
        $this->expectException(\ArgumentCountError::class);

        new CompareValue();
    }

    /**
     * @dataProvider positiveGetOperatorDataProvider
     */
    public function testPositiveGetOperator($expectedOperator)
    {
        $object = new CompareValue('anyvalue', $expectedOperator);

        $operator = $object->getOperator();

        $this->assertEquals($expectedOperator, $operator);
    }

    public function positiveGetOperatorDataProvider(): array
    {
        return [
            ['='],
            ['LIKE']
        ];
    }

    /**
     * @dataProvider negativeGetOperatorDataProvider
     */
    public function testNegativeGetOperator($operator)
    {
        $this->expectException(\TypeError::class);

        new CompareValue('anyvalue', $operator);
    }

    public function negativeGetOperatorDataProvider(): array
    {
        return [
            [null],
            [1],
            [true]
        ];
    }
}
