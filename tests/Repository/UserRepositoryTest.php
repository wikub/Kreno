<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\GenerateSchema;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $schema = new GenerateSchema();
        $schema->generate();
    }

    public function testShouldBeCreateNewUser(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $userRepository = static::getContainer()->get(UserRepository::class);

        $this->assertInstanceOf(UserRepository::class, $userRepository);

        $user = new User();
        $user->setEmail('test@test.com')
            ->setUsername('test')
            ->setPassword('test')
            ->setRoles(['ROLE_USER'])
            ->setName('Test Name')
            ->setFirstname('Test Firstname')
            ->setPhonenumber('Test Phonenumber')
            ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function testShouldBeCreateNewUserAndRemove(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $userRepository = static::getContainer()->get(UserRepository::class);

        $this->assertInstanceOf(UserRepository::class, $userRepository);

        $user = new User();
        $user->setEmail('test2@test.com')
            ->setUsername('test')
            ->setPassword('test')
            ->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function testShouldBeCreateNewUserAndUpgradePasswordWithValidUser(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $userRepository = static::getContainer()->get(UserRepository::class);

        $this->assertInstanceOf(UserRepository::class, $userRepository);

        $user = new User();
        $user->setEmail('test3@test.com')
            ->setUsername('test')
            ->setPassword('test')
            ->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $user->setPassword('test2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
