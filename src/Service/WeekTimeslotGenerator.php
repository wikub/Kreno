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

use App\Entity\Job;
use App\Entity\Timeslot;
use App\Entity\Week;
use App\Repository\WeekRepository;
use App\Repository\WeekTemplateRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class WeekTimeslotGenerator
{
    private $em;
    private $repo;
    private $weekRepo;
    private $timeslotWorkflow;
    private $flash;

    public function __construct(
        EntityManagerInterface $entityManager,
        WeekTemplateRepository $weekTemplateRepository,
        WeekRepository $weekRepository,
        FlashBagInterface $flash,
        WorkflowInterface $timeslotWorkflow
    ) {
        $this->em = $entityManager;
        $this->repo = $weekTemplateRepository;
        $this->timeslotWorkflow = $timeslotWorkflow;
        $this->weekRepo = $weekRepository;
        $this->flash = $flash;
    }

    public function generate(Datetime $start, DateTime $finish, int $ifWeekExist = 1)
    {
        $previousWeekType = 4; //WeekType A
        $week = null;

        if (1 !== $start->format('N')) {
            $start->modify('next monday');
        }

        //Récupère la semaine prècédent
        $previousDate = (clone $start)->modify('-7 days');
        $previousWeek = $this->weekRepo->findByStartDate($previousDate);

        //if( $previousWeek == null ) $previousWeekType = $previousWeek->getWeekType();
        if (null === $previousWeek) {
            $this->flash->add('error', 'Attention : Il n\'a aucune semaine précédente trouvé.');

            return false;
        }

        //Get the template at the right cycle
        $weekTemplates = $this->repo->findAll();
        while ($previousWeekType !== (current($weekTemplates))->getWeekType()) {
            next($weekTemplates);
        }

        //Generate the weeks
        while ($start <= $finish) {
            $weekTemplate = next($weekTemplates);
            if (!$weekTemplate) {
                $weekTemplate = reset($weekTemplates);
            }

            // //Lookfor existing week ?
            $week = $this->weekRepo->findByStartDate($start);

            if (null !== $week) {
                if (1 === $ifWeekExist) { //Week will be ignore
                    $this->flash->add('warning', 'la semaine du '.$start->format('d/m/Y').' existe déjà. Elle a été ignorée.');
                    $start->modify('+7 days');
                    continue;
                }
                $this->em->remove($week);
                $this->em->flush();
                $this->flash->add('warning', 'la semaine du '.$start->format('d/m/Y').' existe déjà. Elle a été supprimée.');
            }

            $week = new Week();
            $week->setWeekType($weekTemplate->getWeekType());
            $week->setStartAt($start);

            $this->em->persist($week);
            //Generate the timeslots
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

                //Workflow
                $this->timeslotWorkflow->apply($timeslot, 'to_open');
                //$timeslot->setStatus(['draft']);
                $this->em->persist($timeslot);

                //Get the commitment contract for job creation
                $nbJobCreated = 0;

                foreach ($timeslotTemplate->getRegularCommitmentContracts() as $regular) {
                    $job = new Job();
                    $job->setUser($regular->getCommitmentContract()->getUser());
                    $job->setTimeslot($timeslot);

                    //If manager
                    if (true === $regular->getCommitmentContract()->getType()->isManager()) {
                        $job->setManager(true);
                    }

                    $this->em->persist($job);
                    ++$nbJobCreated;
                }

                //Empty Job creation
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
        }
    }
}
