<?php

namespace App\Utils\Repositories\Contracts;

use App\Utils\Repositories\CompareValue\CompareValue;
use App\Utils\Repositories\CompareValue\CompareValueCollection;

interface CompareValueFactory
{
    /**
     * @param $value
     * @param string $operator
     * @return CompareValue
     */
    public function createCompareValue($value, string $operator = '='): CompareValue;

    /**
     * @param array $objects
     * @return CompareValueCollection
     */
    public function createCompareValueCollection(array $objects): CompareValueCollection;
}
