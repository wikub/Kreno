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
use App\Repository\CycleRepository;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use App\Service\GetParam;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;

class CycleStartNotification
{
    private ?bool $enable;
    private ?int $nbDaysBefore;
    private LoggerInterface $logger; 
    private EmailSender $emailSender;
    private UserRepository $userRepository;
    private CommitmentLogRepository $commitmentLogRepository;
    private CycleRepository $cycleRepository;

    public function __construct(
        UserRepository $userRepository,
        CommitmentLogRepository $commitmentLogRepository,
        GetParam $getParam,
        LoggerInterface $logger,
        EmailSender $emailSender,
        CycleRepository $cycleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->commitmentLogRepository = $commitmentLogRepository;
        $this->logger = $logger;
        $this->emailSender = $emailSender;
        $this->cycleRepository = $cycleRepository;

        $this->enable = (bool) $getParam->get('EMAIL_NOTIF_START_CYCLE_ENABLE');
        $this->nbDaysBefore = (int) $getParam->get('EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE');
    }

    public function send(Cycle $cycle = null): void
    {
        
        if( !$this->enable ) {
            $this->logger->info('EMAIL_NOTIF_START_CYCLE_ENABLE is disable. the process is cancel');
            throw new Exception('EMAIL_NOTIF_START_CYCLE_ENABLE is disable. the process is cancel');
        }
        
        if( !isset($cycle) && !($this->nbDaysBefore > 0) ) {
            $this->logger->info('Cycle not provides and EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE is not superior to 0. the process is cancel');
            throw new Exception('Cycle not provides and EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE is not superior to 0. the process is cancel');
        }

        if( !isset($cycle) ) {
            $cycle = $this->getCycle();
        }

        if( !isset($cycle) ) {
            $this->logger->info('Cycle not provides and not found. the process is cancel');
            throw new Exception('Cycle not provides and not found. the process is cancel');
        }

        $users = $this->getUsers();
        
        foreach($users as $user) {
            if( $user->getEmail() === null ) continue;

            $balance = $this->commitmentLogRepository->getNbTimeslotAndHourBalance($user);

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

    private function getCycle(): ?Cycle
    {
        $date = (new \DateTimeImmutable());//->modify('+'.$this->nbDaysBefore.' days');
        return $this->cycleRepository->findOneBy(['start' => $date]);
    }

    private function getUsers(): array
    {
        return $this->userRepository->findBy(['enabled' => true]);
    }
}