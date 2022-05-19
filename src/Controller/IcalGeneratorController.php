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

use App\Repository\UserRepository;
use App\Service\CalendarGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ical", name="ical_")
 */
class IcalGeneratorController extends AbstractController
{
    private $calendarGenerator;
    private $userRepository;

    public function __construct(
        CalendarGenerator $calendarGenerator,
        UserRepository $userRepository
    ) {
        $this->calendarGenerator = $calendarGenerator;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/{token}.ics", name="personnal_calendar")
     */
    public function personnalCalendar(string $token = null)
    {
        if (64 !== mb_strlen($token)) {
            return new Response('Not Found', Response::HTTP_FORBIDDEN);
        }

        $user = $this->userRepository->findByCalendarToken($token);
        if (null === $user) {
            return new Response('Not Found', Response::HTTP_FORBIDDEN);
        }

        $content = $this->calendarGenerator->generateForUser($user);

        $response = new Response();

        $response->setContent($content);
        $response->headers->set('Content-Type', 'text/calendar; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="cal.ics');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    /**
     * @Route("/calendar.ics", name="calendar")
     */
    public function calendar(string $token = null)
    {
        if (64 !== mb_strlen($token)) {
            return new Response('Not Found', Response::HTTP_FORBIDDEN);
        }

        $user = $this->userRepository->findByCalendarToken($token);
        if (null === $user) {
            return new Response('Not Found', Response::HTTP_FORBIDDEN);
        }

        $content = $this->calendarGenerator->generateForUser($user);

        $response = new Response();

        $response->setContent($content);
        $response->headers->set('Content-Type', 'text/calendar; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="cal.ics');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }
}
