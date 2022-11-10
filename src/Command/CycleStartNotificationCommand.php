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

use App\Service\Notification\CycleStartNotification;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CycleStartNotificationCommand extends Command
{
    protected static $defaultName = 'app:notification:cycle-start';
    protected static $defaultDescription = 'Send notification for cycle start';
    private CycleStartNotification $cycleStartNotification;

    public function __construct(CycleStartNotification $cycleStartNotification)
    {
        parent::__construct();

        $this->cycleStartNotification = $cycleStartNotification;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->cycleStartNotification->send();
            $io->success('Les notifications ont été envoyé');
        } catch (\Exception $e) {
            $io->warning($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
