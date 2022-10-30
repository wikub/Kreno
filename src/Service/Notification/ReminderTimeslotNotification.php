<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Notification;

use App\Repository\TimeslotRepository;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use App\Service\GetParam;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ReminderTimeslotNotification
{
    private ?bool $enable;
    private ?int $nbHourBefore;
    private LoggerInterface $logger; 
    private EmailSender $emailSender;
    private TimeslotRepository $timeslotRepository;

    public function __construct(
        TimeslotRepository $timeslotRepository,
        GetParam $getParam,
        LoggerInterface $logger,
        EmailSender $emailSender
    ) {
        $this->timeslotRepository = $timeslotRepository;
        $this->logger = $logger;
        $this->emailSender = $emailSender;

        $this->enable = $getParam->get('EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE');

        $this->nbHourBefore = $getParam->get('EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOUR_BEFORE');
        if( $this->nbHourBefore == null || $this->nbHourBefore <= 0 ) $this->nbHourBefore = 72;
    }

    public function send(): void
    {
        if( !$this->enable ) {
            $this->logger->info('EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE is disable. the process is cancel');
            throw new Exception('EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE is disable. the process is cancel');
        } 

        $timeslots = $this->timeslotRepository->findUpcoming($this->nbHourBefore, 24);

        foreach($timeslots as $timeslot) {
            foreach($timeslot->getJobs() as $job) {
                if( $job->getUser() === null ) continue;
                if( $job->getUser()->getEmail() === null ) continue;

                $adress = new Address($job->getUser()->getEmail(), $job->getUser()->getFirstname().' '.$job->getUser()->getName());

                $this->emailSender->sendWithEmailTemplate(
                    'EMAIL_NOTIF_REMINDER_TIMESLOT', 
                    $adress, 
                    [
                        'user' => $job->getUser(),
                        'timeslot' => $timeslot,
                        'job' => $job
                    ]
                );
            }
        }

    }
}
