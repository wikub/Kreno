<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\UserActivity;
use Doctrine\ORM\EntityManagerInterface;

class UserActivityAdd
{
    public const ACTION_LOGIN = 1;
    public const ACTION_JOB_SUBSCRIPTION = 2;
    public const ACTION_JOB_UNSUBSCRIPTION = 3;

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function add(User $user, int $actionType, array $context = null)
    {
        $activity = new UserActivity();
        $activity->setUser($user);
        $activity->setActionType($actionType);
        $activity->setContext($context);

        $this->em->persist($activity);
        $this->em->flush();
    }
}
