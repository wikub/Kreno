<?php

declare(strict_types=1);

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Functional\Setup;

use App\Entity\User;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Role\Role;

class UserContext implements Context
{
    private EntityManagerInterface $entityManager;

    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(EntityManagerInterface $entityManager, PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasherFactory = $passwordHasherFactory;
    }

    /**
     * @Given The user :arg1 is created with this password :arg2
     */
    public function registerUser($email, $password)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($email);
        $user->setName('test');
        $user->setFirstname('test');
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher($user)->hash($password));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @Given The user :name :firstname with :email is created
     */
    public function UserIsCreated($name, $firstname, $email)
    {
        return $this->createUser($name, $firstname, $email, ['ROLE_USER']);
    }

    /**
     * @Given The user admin :name :firstname with :email is created
     */
    public function UserAdminIsCreated($name, $firstname, $email)
    {
        return $this->createUser($name, $firstname, $email, ['ROLE_ADMIN']);
    }

    /**
     * @Given The user commitment manager :name :firstname with :email is created
     */
    public function UserCommitmentManagerIsCreated($name, $firstname, $email)
    {
        return $this->createUser($name, $firstname, $email, ['ROLE_COMMIT']);
    }

    private function createUser($name, $firstname, $email, array $roles): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($email);
        $user->setName($name);
        $user->setFirstname($firstname);
        $user->setRoles($roles);
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher($user)->hash($email));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
