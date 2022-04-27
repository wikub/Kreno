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

use App\Entity\CommitmentLog;
use App\Entity\Job;
use App\Entity\Timeslot;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use LogicException;
use Symfony\Component\Workflow\WorkflowInterface;

class TimeslotEventListener
{
    private $timeslotWorkflow;
    private $em;

    public function __construct(WorkflowInterface $timeslotWorkflow, EntityManagerInterface $entityManager)
    {
        $this->timeslotWorkflow = $timeslotWorkflow;
        $this->em = $entityManager;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postUpdate(Timeslot $timeslot, LifecycleEventArgs $event): void
    {
        $changesTimeslot = $this->em->getUnitOfWork()->getEntityChangeSet($timeslot);

        // Validation
        if (isset($changesTimeslot['status']) && $timeslot->isValidated()) {
            $this->fromValidatedToCommimentLogged($timeslot);
        }

        // Cancel Validation
        if (isset($changesTimeslot['status']) && \array_key_exists('commitment_logged', $changesTimeslot['status'][0]) && \array_key_exists('open', $changesTimeslot['status'][1])) {
            $this->fromCommimentLoggedToOpen($timeslot);
        }

        $this->em->flush();
    }

    private function fromValidatedToCommimentLogged(Timeslot $timeslot)
    {
        try {
            $this->timeslotWorkflow->apply($timeslot, 'to_commitment_log');
        } catch (LogicException $exception) {
            return;
        }

        foreach ($timeslot->getJobs() as $job) {
            if (null === $job->getUser()) {
                continue;
            }

            if ($job->getTimeslot()->isCommitmentLogged()) {
                // add commitmentlog credit
                if ($job->getJobDone()->getCommitmentCalculation()) {
                    $job->setCommitmentLog($this->addCommitmentLog($job));
                }
            }
            $this->em->persist($job);
        }

        $this->em->persist($timeslot);
    }

    private function fromCommimentLoggedToOpen(Timeslot $timeslot)
    {
        foreach ($timeslot->getJobs() as $job) {
            if (null === $job->getCommitmentLog()) {
                continue;
            }

            $this->addCancelCommitmentLog($job);
            $job->setCommitmentLog(null);

            $this->em->persist($job);
        }

        $this->em->persist($timeslot);
    }

    private function addCancelCommitmentLog(Job $job)
    {
        $commitLog = new CommitmentLog();
        $commitLog->setUser($job->getUser());
        $commitLog->setNbTimeslot(-1 * $job->getCommitmentLog()->getNbTimeslot());
        $commitLog->setComment('Annulation : '.$job->getCommitmentLog()->getComment());
        $this->em->persist($commitLog);
    }

    private function addCommitmentLog(Job $job): CommitmentLog
    {
        $commitLog = new CommitmentLog();
        $commitLog->setUser($job->getUser());
        $commitLog->setNbTimeslot(1);
        $commitLog->setComment($job->getTimeslot()->getDisplayName().' '.$job->getTimeslot()->getDisplayDateInterval());
        $this->em->persist($commitLog);

        return $commitLog;
    }
}
