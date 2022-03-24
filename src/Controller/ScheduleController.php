<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Filter\Form\UserWeekSchedulerType;
use App\Filter\UserWeekScheduler;
use App\Repository\WeekRepository;
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
        if (null === $week) {
            $week = $weekRepository->findOneBy(['startAt' => new \DateTime('monday this week')]);
        }

        return $this->renderForm('schedule/index.html.twig', [
            'week' => $week,
            'formFilter' => $formFilter,
        ]);
    }
}
