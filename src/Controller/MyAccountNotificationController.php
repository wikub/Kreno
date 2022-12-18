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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/me/notification", name="myaccount_notification_")
 */
class MyAccountNotificationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/timeslot-reminder/enable", name="timeslot_reminder_enable")
     */
    public function timeslotReminderEnable(): Response
    {
        /* @var User */
        $user = $this->getUser();

        $user->setEmailNotifTimeslotReminder(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'La notification de rappel de début de créneau a été activé.');

        return $this->redirectToRoute('myaccount_index');
    }

    /**
     * @Route("/timeslot-reminder/disable", name="timeslot_reminder_disable")
     */
    public function timeslotReminderDisable(): Response
    {
        /* @var User */
        $user = $this->getUser();
        $user->setEmailNotifTimeslotReminder(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'La notification de rappel de début de créneau a été desactivé.');

        return $this->redirectToRoute('myaccount_index');
    }

    /**
     * @Route("/cycle-start/enable", name="cycle_start_enable")
     */
    public function cycleStartEnable(): Response
    {
        /* @var User */
        $user = $this->getUser();

        $user->setEmailNotifCycleStart(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'La notification de début de cycle a été activé.');

        return $this->redirectToRoute('myaccount_index');
    }

    /**
     * @Route("/cycle-start/disable", name="cycle_start_disable")
     */
    public function cycleStartDisable(): Response
    {
        /* @var User */
        $user = $this->getUser();

        $user->setEmailNotifCycleStart(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'La notification de début de cycle a été desactivé.');

        return $this->redirectToRoute('myaccount_index');
    }
}
