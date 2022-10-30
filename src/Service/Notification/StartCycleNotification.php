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

use App\Entity\Cycle;
use App\Repository\CommitmentLogRepository;
use App\Repository\TimeslotRepository;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use App\Service\GetParam;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class StartCycleNotification
{
    private ?bool $enable;
    private LoggerInterface $logger; 
    private EmailSender $emailSender;
    private UserRepository $userRepository;
    private CommitmentLogRepository $commitmentLogRepository;

    public function __construct(
        UserRepository $userRepository,
        CommitmentLogRepository $commitmentLogRepository,
        GetParam $getParam,
        LoggerInterface $logger,
        EmailSender $emailSender
    ) {
        $this->userRepository = $userRepository;
        $this->commitmentLogRepository = $commitmentLogRepository;
        $this->logger = $logger;
        $this->emailSender = $emailSender;

        $this->enable = $getParam->get('EMAIL_NOTIF_START_CYCLE_ENABLE');
    }

    public function send(Cycle $cycle): void
    {
        if( !$this->enable ) {
            $this->logger->info('EMAIL_NOTIF_START_CYCLE_ENABLE is disable. the process is cancel');
        } 

        $users = $this->userRepository->findBy(['enabled' => true]);
        
        foreach($users as $user) {
            if( $user->getEmail() === null ) continue;

            $balance = $this->commitmentLogRepository->getNbTimeslotAndHourBalance($user);
            dump($balance);
            $adress = new Address($user->getEmail(), $user->getFirstname().' '.$user->getName());

            $this->emailSender->sendWithEmailTemplate(
                'EMAIL_NOTIF_START_CYCLE', 
                $adress, 
                [
                    'user' => $user,
                    'cycle' => $cycle,
                    'hourBalance' => $balance['sumNbHour'],
                    'timeslotBalance' => $balance['sumNbTimeslot'],
                ]
            );
        }

    }
}
