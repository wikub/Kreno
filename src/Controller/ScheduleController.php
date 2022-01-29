<?php

namespace App\Controller;

use App\Entity\Timeslot;
use App\Repository\TimeslotRepository;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/schedule", name="schedule_")
 */
class ScheduleController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(TimeslotRepository $timeslotRepository): Response
    {
        $lastMonday = new \DateTime('last monday');
        $week = array();
        for($i = 1; $i <= 7;  $i++) {
            $week[$i]['date'] = clone($lastMonday);
            $week[$i]['timeslots'] = array();
            $lastMonday->add(new DateInterval('P1D'));
        }

        //Récupère la liste timeslot de la semaine
        $timeslots = $timeslotRepository->findByWeek($lastMonday);
        foreach($timeslots as $timeslot) {
            $dayOfWeek = $timeslot->getStart()->format('N');
            $week[$dayOfWeek]['timeslots'][] = $timeslot;
        }

        return $this->render('schedule/index.html.twig', [

            'week' => $week,
        ]);
    }
}
