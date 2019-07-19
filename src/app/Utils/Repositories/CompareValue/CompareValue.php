<?php declare(strict_types=1);

namespace App\Utils\Repositories\CompareValue;


class CompareValue
{
    private $value;
    /**
     * @var string
     */
    private $operator;

    public function __construct($value, string $operator = '=')
    {
        $this->value = $value;
        $this->operator = $operator;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

}
