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

use App\Repository\JobDoneTypeRepository;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class TimeslotAutoValidation
{
    private $em;
    private $repo;
    private $timeslotWorkflow;
    private $flash;
    private $repoJobDoneType;

    public function __construct(
        EntityManagerInterface $entityManager,
        TimeslotRepository $timeslotRepository,
        FlashBagInterface $flash,
        WorkflowInterface $timeslotWorkflow,
        JobDoneTypeRepository $jobDoneTypeRepository
    ) {
        $this->em = $entityManager;
        $this->repo = $timeslotRepository;
        $this->timeslotWorkflow = $timeslotWorkflow;
        $this->flash = $flash;
        $this->repoJobDoneType = $jobDoneTypeRepository;
    }

    public function timeslotAutoValidation()
    {
        if (!($timeslots = $this->repo->findForValidation24h())) {
            $this->flash->add('notice', 'Aucun créneau à valider');

            return;
        }

        $nowText = (new \DateTime())->format('d/m/Y H:i');

        $this->timeslotValidation($timeslots, 'Auto-Validation automatique du '.$nowText);
    }

    public function timeslotSelectionValidation(array $timeslotsSelection)
    {
        $timeslots = $this->repo->findSelection($timeslotsSelection);
        $nowText = (new \DateTime())->format('d/m/Y H:i');

        $this->timeslotValidation($timeslots, 'Auto-Validation manuel du '.$nowText);
    }

    private function timeslotValidation(array $timeslots, $comment = '')
    {
        if (!($jobDone = $this->repoJobDoneType->findDefaultValue())) {
            $this->flash->add('notice', 'Aucune valeur par défaut définit');

            return;
        }

        foreach ($timeslots as $timeslot) {
            foreach ($timeslot->getJobs() as $job) {
                if (null === $job->getUser()) {
                    continue;
                }

                $job->setJobDone($jobDone);
                $this->em->persist($job);
            }

            $timeslot->setCommentValidation($comment);

            try {
                $this->timeslotWorkflow->apply($timeslot, 'to_admin_validated');
            } catch (\LogicException $exception) {
                $this->flash->add('error', 'L\'opération ne peut pas être réalisée [workflow]');
                continue;
            }

            $this->em->persist($timeslot);
            $this->em->flush();

            $this->flash->add('notice', 'Le créneau '.$timeslot->getDisplayName().' '.$timeslot->getDisplayDateInterval().' a été validé');
        }
    }
}
