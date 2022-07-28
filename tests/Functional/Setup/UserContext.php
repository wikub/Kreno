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
     * @Given l'utilisateur :arg1 est enregistré avec le mot de passe :arg2
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
}
