<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Service\Notification\ReminderTimeslotNotification;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReminderTimeslotNotificationCommand extends Command
{
    protected static $defaultName = 'app:notification:reminder-timeslot';
    protected static $defaultDescription = 'Send notification to reminder timeslot subscription';
    private ReminderTimeslotNotification $reminderTimeslotNotification;

    public function __construct(ReminderTimeslotNotification $reminderTimeslotNotification)
    {
        parent::__construct();

        $this->reminderTimeslotNotification = $reminderTimeslotNotification;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->reminderTimeslotNotification->send();
        } catch (\Exception $e) {
            $io->warning($e->getMessage());
        }

        $io->success('Les notifications ont été envoyé');

        return Command::SUCCESS;
    }
}
