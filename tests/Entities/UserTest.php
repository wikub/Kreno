<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entities;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public const USER_EMAIL = 'test@test.com';
    public const USER_PASSWORD = 'test';
    public const USER_ROLES = ['ROLE_USER'];
    public const USER_NAME = 'Test Nom';
    public const USER_FIRSTAME = 'Jean Test';
    public const USER_PHONE_NUMBER = '+336589874566598';

    public function testIfTrue(): void
    {
        $user = $this->getUser();

        $this->assertSame(self::USER_EMAIL, $user->getEmail());
        $this->assertSame(self::USER_PASSWORD, $user->getPassword());
        $this->assertSame(self::USER_ROLES, $user->getRoles());
        $this->assertSame(self::USER_NAME, $user->getName());
        $this->assertSame(self::USER_FIRSTAME, $user->getFirstname());
        $this->assertSame(self::USER_PHONE_NUMBER, $user->getPhonenumber());
    }

    public function testIfNotNull(): void
    {
        $user = $this->getUser();

        $this->assertNotNull($user->getEmail());
        $this->assertNotNull($user->getPassword());
        $this->assertNotNull($user->getRoles());
        $this->assertNotNull($user->getName());
        $this->assertNotNull($user->getFirstname());
        $this->assertNotNull($user->getPhonenumber());
    }

    public function testIfNull(): void
    {
        $user = new User();

        $this->assertNull($user->getEmail());
        $this->assertNull($user->getId());
        $this->assertNull($user->getName());
        $this->assertNull($user->getFirstname());
        $this->assertNull($user->getPhonenumber());
    }

    public function getUser(): User
    {
        $user = new User();

        $user->setEmail(self::USER_EMAIL)
            ->setPassword(self::USER_PASSWORD)
            ->setRoles(self::USER_ROLES)
            ->setName(self::USER_NAME)
            ->setFirstname(self::USER_FIRSTAME)
            ->setPhonenumber(self::USER_PHONE_NUMBER)
        ;

        return $user;
    }
}
