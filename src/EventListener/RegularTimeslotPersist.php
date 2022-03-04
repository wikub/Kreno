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

use App\Entity\CommitmentContractRegularTimeslot;
use App\Entity\Job;
use App\Repository\JobRepository;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class RegularTimeslotPersist
{
    private $em;
    private $jobRepository;
    private $timeslotRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        JobRepository $jobRepository,
        TimeslotRepository $timeslotRepository
    ) {
        $this->em = $entityManager;
        $this->jobRepository = $jobRepository;
        $this->timeslotRepository = $timeslotRepository;
    }

    public function postUpdate(CommitmentContractRegularTimeslot $regular, LifecycleEventArgs $event): void
    {
        // $changed = $this->em->getUnitOfWork()->getEntityChangeSet($regular);
        // if( !array_key_exists('timeslotTemplate', $changed) and !array_key_exists('start', $changed) ) return;

        //Delete
        $this->removeUserFromFuturOldJobs($regular);

        //New
        $this->subscribeToFuturJobs($regular);

        $this->em->flush();
    }

    public function postPersist(CommitmentContractRegularTimeslot $regular, LifecycleEventArgs $event): void
    {
        $this->subscribeToFuturJobs($regular);

        $this->em->flush();
    }

    private function subscribeToFuturJobs(CommitmentContractRegularTimeslot $regular)
    {
        $timeslotsToSubscribe = $this->timeslotRepository->findFutureByTimeslotTemplate($regular->getTimeslotTemplate(), $regular->getStart(), $regular->getFinish());

        foreach ($timeslotsToSubscribe as $timeslot) {
            if ($regular->getCommitmentContract()->getType()->isManager()) {
                $job = $timeslot->getFreeManagerJobs()->first();
            } else {
                $job = $timeslot->getFreeNoManagerJobs()->first();
            }

            if (false === $job) {
                $job = new Job();
                $job->setUser($regular->getCommitmentContract()->getUser());
                $job->setTimeslot($timeslot);

                if (true === $regular->getCommitmentContract()->getType()->isManager()) {
                    $job->setManager(true);
                }
            } else {
                $job->setUser($regular->getCommitmentContract()->getUser());
            }

            $this->em->persist($job);
        }
    }

    private function removeUserFromFuturOldJobs(CommitmentContractRegularTimeslot $regular)
    {
        $changed = $this->em->getUnitOfWork()->getEntityChangeSet($regular);
        if (\array_key_exists('timeslotTemplate', $changed)) {
            $timeslotTemplate = $changed['timeslotTemplate'][0];
        } else {
            $timeslotTemplate = $regular->getTimeslotTemplate();
        }

        $oldFuturJobs = $this->jobRepository->getFuturForRegular($timeslotTemplate, $regular->getCommitmentContract()->getUser());
        foreach ($oldFuturJobs as $job) {
            $job->setUser(null);
            $this->em->persist($job);
        }
    }
}
