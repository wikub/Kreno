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

use App\Entity\Timeslot;
use App\Entity\User;
use App\Repository\TimeslotRepository;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;

class CalendarGenerator
{
    private $timeslotRepository;

    public function __construct(
        TimeslotRepository $timeslotRepository
    ) {
        $this->timeslotRepository = $timeslotRepository;
    }

    public function generate(): string
    {
        $events = [];
        foreach ($this->timeslotRepository->findAllActive() as $timeslot) {
            $events[] = $this->transformToEvent($timeslot);
        }

        // 2. Create Calendar domain entity
        $calendar = new Calendar($events);

        // 3. Transform domain entity into an iCalendar component
        $componentFactory = new CalendarFactory();
        $calendarComponent = $componentFactory->createCalendar($calendar);

        // 5. Output
        return $calendarComponent;
    }

    public function generateForUser(User $user): string
    {
        $events = [];
        foreach ($this->timeslotRepository->findAllActiveWhenUserSubscribe($user) as $timeslot) {
            $events[] = $this->transformToEvent($timeslot);
        }

        // 2. Create Calendar domain entity
        $calendar = new Calendar($events);

        // 3. Transform domain entity into an iCalendar component
        $componentFactory = new CalendarFactory();
        $calendarComponent = $componentFactory->createCalendar($calendar);

        // 5. Output
        return $calendarComponent;
    }

    public function generateForSupervisor(): string
    {
        $events = [];
        foreach ($this->timeslotRepository->findAllActive() as $timeslot) {
            $events[] = $this->transformToEvent($timeslot, true);
        }

        // 2. Create Calendar domain entity
        $calendar = new Calendar($events);

        // 3. Transform domain entity into an iCalendar component
        $componentFactory = new CalendarFactory();
        $calendarComponent = $componentFactory->createCalendar($calendar);

        // 5. Output
        return $calendarComponent;
    }

    private function transformToEvent(Timeslot $timeslot, bool $supervisor = false): Event
    {
        $event = new Event();
        $event
            ->setSummary($timeslot->getName())
            ->setOccurrence(
                new TimeSpan(
                    new DateTime($timeslot->getStart(), false),
                    new DateTime($timeslot->getFinish(), false)
                )
            )
            ;

        if ($supervisor) {
            $found = false;
            $description = 'Avec ';
            foreach ($timeslot->getJobs() as $job) {
                if (null !== $job->getUser()) {
                    $description .= '- '.$job->getUser()->getFirstname().' '.$job->getUser()->getName().' ';
                    $found = true;
                }
            }

            if ($found) {
                $event->setDescription($description);
            }
        }

        return $event;
    }
}
