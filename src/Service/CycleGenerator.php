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

use App\Entity\Cycle;
use App\Entity\Job;
use App\Entity\Timeslot;
use App\Entity\Week;
use App\Repository\WeekTemplateRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class CycleGenerator
{
    private $em;
    private $repo;
    private $timeslotWorkflow;
    private $flash;

    public function __construct(
        EntityManagerInterface $entityManager,
        WeekTemplateRepository $weekTemplateRepository,
        FlashBagInterface $flash,
        WorkflowInterface $timeslotWorkflow
    ) {
        $this->em = $entityManager;
        $this->repo = $weekTemplateRepository;
        $this->timeslotWorkflow = $timeslotWorkflow;
        $this->flash = $flash;
    }

    public function generate(Datetime $start, DateTime $finish)
    {
        // Cycle creation
        $cycle = new Cycle();
        $cycle->setStart($start);
        $cycle->setFinish($finish);

        $this->em->persist($cycle);

        // Get the template at the right cycle
        $weekTemplates = $this->repo->findAll();

        $weekTemplate = reset($weekTemplates);
        // Generate the weeks
        while ($start <= $finish) {
            $week = new Week();
            $week->setCycle($cycle);
            $week->setWeekType($weekTemplate->getWeekType());
            $week->setStartAt($start);

            $this->em->persist($week);
            // Generate the timeslots
            foreach ($weekTemplate->getTimeslotTemplates() as $timeslotTemplate) {
                $timeslot = new Timeslot();

                $timeslot->setName($timeslotTemplate->getName());
                $timeslot->setTimeslotType($timeslotTemplate->getTimeslotType());
                $timeslot->setWeek($week);
                $timeslot->setDescription($timeslotTemplate->getDescription());

                $startTimeslotAt = clone $week->getStartAt();
                $startTimeslotAt->modify('+ '.($timeslotTemplate->getDayWeek() - 1).' days');
                $startTimeslotAt->setTime($timeslotTemplate->getStart()->format('H'), $timeslotTemplate->getStart()->format('i'));
                $timeslot->setStart($startTimeslotAt);

                $finishTimeslotAt = clone $week->getStartAt();
                $finishTimeslotAt->modify('+ '.($timeslotTemplate->getDayWeek() - 1).' days');
                $finishTimeslotAt->setTime($timeslotTemplate->getFinish()->format('H'), $timeslotTemplate->getFinish()->format('i'));
                $timeslot->setFinish($finishTimeslotAt);

                $timeslot->setTemplate($timeslotTemplate);

                // Workflow
                $this->timeslotWorkflow->apply($timeslot, 'to_open');
                // $timeslot->setStatus(['draft']);
                $this->em->persist($timeslot);

                // Get the commitment contract for job creation
                $nbJobCreated = 0;

                foreach ($timeslotTemplate->getRegularCommitmentContracts() as $regular) {
                    $job = new Job();
                    $job->setUser($regular->getCommitmentContract()->getUser());
                    $job->setTimeslot($timeslot);

                    // If manager
                    if (true === $regular->getCommitmentContract()->getType()->isManager()) {
                        $job->setManager(true);
                    }

                    $this->em->persist($job);
                    ++$nbJobCreated;
                }

                // Empty Job creation
                $nbEmptyJobToCreate = $timeslotTemplate->getNbJob() - $nbJobCreated;
                for ($i = 0; $i < $nbEmptyJobToCreate; ++$i) {
                    $job = new Job();
                    $job->setTimeslot($timeslot);
                    $this->em->persist($job);
                }
            }

            $this->em->flush();
            $this->flash->add('success', 'la semaine du '.$start->format('d/m/Y').' a été créée.');

            $start->modify('+7 days');
            $weekTemplate = next($weekTemplates);
        }
    }
}
