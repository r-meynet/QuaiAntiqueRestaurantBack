<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testTheAutomaticApiTokenSettingWhenUserIsCreated(): void
    {
        $user = new User();
        $this->assertNotNull($user->getApiToken());
    }

    public function testThatAnUserHasAtLeastOneRoleUser(): void
    {
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public static function provideFirstName(): iterable
    {
        yield ['Sohan'];
        yield ['Eric'];
        yield ['Micah'];
    }

    #[DataProvider('provideFirstName')]
    public function testFirstNameSetter(string $name): void
    {
        $user = new User();
        $user->setFirstName($name);

        $this->assertEquals($name, $user->getFirstName());
    }

    // public function testAnException(): void
    // {
    //     $this->expectException(\TypeError::class);

    //     $user = new User();
    //     $user->setFirstName([10]);
    // }
}
