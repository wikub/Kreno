<?php

namespace App\Controller;

use App\Entity\Week;
use App\Filter\Form\UserWeekSchedulerType;
use App\Filter\UserWeekScheduler;
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
     * @Route("", name="index")
     */
    public function index(WeekRepository $weekRepository, Request $request, UserWeekScheduler $filter): Response
    {
        $formFilter = $this->createForm(UserWeekSchedulerType::class, $filter);
        $formFilter->handleRequest($request);

        $week = $filter->getWeek();
        if( $week == null ) {
            $week = $weekRepository->findOneBy(['startAt' => new \DateTime('last monday')]);
        }

        return $this->renderForm('schedule/index.html.twig', [
            'week' => $week,
            'formFilter' => $formFilter
        ]);
    }
}
