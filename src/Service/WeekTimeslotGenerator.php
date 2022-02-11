<?php

namespace App\Service;

use App\Entity\Job;
use App\Entity\Timeslot;
use App\Entity\Week;
use App\Repository\WeekTemplateRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class WeekTimeslotGenerator 
{
    private $em;
    private $repo;

    public function __construct(EntityManagerInterface $entityManager, WeekTemplateRepository $weekTemplateRepository)
    {
        $this->em = $entityManager;
        $this->repo = $weekTemplateRepository;
    }

    public function generate(Datetime $start, DateTime $finish) 
    {
        if( $start->format('N') != 1 ) {
            $start->modify('next monday');
        }
        
        $weekTemplates = $this->repo->findAll();

        while( $start <= $finish ) {    
            foreach($weekTemplates as $weekTemplate) {
                $week = New Week();
                $week->setName($weekTemplate->getName());
                $week->setWeekType($weekTemplate->getWeekType());
                $week->setStartAt($start);

                $this->em->persist($week);

                foreach($weekTemplate->getTimeslotTemplates() as $timeslotTemplate) {
                    $timeslot = new Timeslot();

                    $timeslot->setName($timeslotTemplate->getName());
                    $timeslot->setTimeslotType($timeslotTemplate->getTimeslotType());
                    $timeslot->setWeek($week);
                    $timeslot->setDescription($timeslotTemplate->getDescription());
                    
                    $startTimeslotAt = clone($week->getStartAt());
                    $startTimeslotAt->modify('+ '.($timeslotTemplate->getDayWeek()-1).' days');
                    $startTimeslotAt->setTime($timeslotTemplate->getStart()->format('H'), $timeslotTemplate->getStart()->format('m'));
                    $timeslot->setStart($startTimeslotAt);

                    $finishTimeslotAt = clone($week->getStartAt());
                    $finishTimeslotAt->modify('+ '.($timeslotTemplate->getDayWeek()-1).' days');
                    $finishTimeslotAt->setTime($timeslotTemplate->getFinish()->format('H'), $timeslotTemplate->getFinish()->format('m'));
                    $timeslot->setFinish($finishTimeslotAt);

                    $timeslot->setTemplate($timeslotTemplate);
                    $this->em->persist($timeslot);

                    $userForThisTimeslot = [];
                    $manager = null;
                    foreach($timeslotTemplate->getRegularCommitmentContracts() as $regular) {
                        if( $regular->getCommitmentContract()->getType()->getManager() == true ) {
                            $timeslot->setManager($regular->getCommitmentContract()->getUser());
                            $this->em->persist($timeslot);
                        } else {
                            $userForThisTimeslot[] = $regular->getCommitmentContract()->getUser();
                        }
                    }

                    //Job creation
                    for($i=0; $i<$timeslotTemplate->getNbJob(); $i++) {
                        $job = new Job();
                        if( key_exists($i, $userForThisTimeslot) ) {
                            $job->setUser($userForThisTimeslot[$i]);
                        }
                        $job->setTimeslot($timeslot);
                        $this->em->persist($job);
                    }

                }
                
                $start->modify('+7 days');
                $this->em->flush();
            }
        }
        
    }
}