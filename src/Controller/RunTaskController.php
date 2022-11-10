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

use App\Service\Notification\CycleStartNotification;
use App\Service\Notification\ReminderTimeslotNotification;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/run", name="run_task_")
 */
class RunTaskController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/reminder-timeslot-notification", name="reminder_timeslot_notification")
     */
    public function reminderTimeslot(
        ReminderTimeslotNotification $reminderTimeslotNotification
    ): Response {
        try {
            $reminderTimeslotNotification->send();
        } catch (\Exception $e) {
            $this->logger->error('Run Task Reminder Timeslot Notification Error : '.$e->getMessage());

            return (new Response())
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return (new Response())
            ->setContent('Reminder Timeslot Notification sended')
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @Route("/cycle-start-notification", name="start_cycle_notification")
     */
    public function startCyle(CycleStartNotification $cycleStartNotification): Response
    {
        try {
            $cycleStartNotification->send();
        } catch (\Exception $e) {
            $this->logger->error('Run Task Cycle Start Notification Error : '.$e->getMessage());

            return (new Response())
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return (new Response())
            ->setContent('Cycle Start Notification sended')
            ->setStatusCode(Response::HTTP_OK);
    }
}
