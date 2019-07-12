<?php

namespace Tests\Support;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\Traits\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
