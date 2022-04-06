<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Entity\CommitmentContract;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CommitmentContractPersist
{
    private $em;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->em = $entityManager;
    }

    public function preUpdate(CommitmentContract $contract, LifecycleEventArgs $event): void
    {
        foreach ($contract->getRegularTimeslots() as $regular) {
            $regular->setStart($contract->getStartCycle()->getStart());
            if ($contract->getFinishCycle()) {
                $regular->setFinish($contract->getFinishCycle()->getFinish());
            }
            $this->em->persist($regular);
        }
        $this->em->flush();
    }

    public function postPersist(CommitmentContract $contract, LifecycleEventArgs $event): void
    {
    }
}
