<?php

namespace Tests\Support\Domain\User\Model;

use App\Domain\User\Model\User;
use Tests\Support\TestCase;
use Tests\Support\Traits\UsedModelFactory;
use TypeError;

class UserTest extends TestCase
{
    use UsedModelFactory;

    public function testPositiveNameAttribute(): void
    {
        /** @var User $user */
        $user = $this->factory(User::class)->make();
        $name = 'username';

        $user->name = $name;

        $this->assertEquals($name, $user->name);
    }

    public function testNegativeNameAttribute(): void
    {
        $this->expectException(TypeError::class);

        /** @var User $user */
        $user = $this->factory(User::class)->make();
        $name = null;

        $user->name = $name;
    }
}
