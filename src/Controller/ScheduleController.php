<?php

namespace App\Controller;

use App\Entity\Timeslot;
use App\Entity\Week;
use App\Repository\TimeslotRepository;
use App\Repository\WeekRepository;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/schedule", name="schedule_")
 */
class ScheduleController extends AbstractController
{
    /**
     * @Route("/{week}", name="index")
     */
    public function index(WeekRepository $weekRepository, TimeslotRepository $timeslotRepository, Request $request, Week $week = null): Response
    {
        
        if( $week == null ) {
            $week = $weekRepository->findOneBy(['startAt' => new \DateTime('last monday')]);
        }
        
        if( $week == null) {
            $curDay = new \DateTime('last monday');
        } else {
            $curDay = clone($week->getStartAt());
        }

        //Init dayOfWeek array
        $dayOfweek = array();
        for($i = 1; $i <= 7;  $i++) {
            $dayOfweek[$i]['date'] = clone($curDay);
            $dayOfweek[$i]['timeslots'] = array();
            $curDay->add(new DateInterval('P1D'));
        }

        //Get timeslots for each day of week
        if( $week ) {
            $timeslots = $timeslotRepository->findByWeek($week);
            foreach($timeslots as $timeslot) {
                $nDayOfWeek = $timeslot->getStart()->format('N');
                $dayOfweek[$nDayOfWeek]['timeslots'][] = $timeslot;
            }
        }
        

        return $this->render('schedule/index.html.twig', [
            'dayOfWeek' => $dayOfweek,
        ]);
    }
}
