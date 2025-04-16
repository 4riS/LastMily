<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $user = new User();

        $email = 'test@example.com';
        $password = '12345678';
        $roles = ['ROLE_USER'];

        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles($roles);

        $this->assertSame($email, $user->getEmail());
        $this->assertSame($email, $user->getUserIdentifier());
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($password, $user->getPassword());

        $this->assertContains('ROLE_USER', $user->getRoles());

        $user->eraseCredentials();
        $this->assertTrue(true);
    }
}
