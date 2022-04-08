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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TestResetCommand extends Command
{
    protected static $defaultName = 'app:reset-db-test';
    protected static $defaultDescription = 'Resets database for tests';

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ('test' !== $this->params->get('kernel.environment')) {
            $output->writeln('this command should be run only in test environment');

            return Command::FAILURE;
        }

        // Création de la base de données
        $command = $this->getApplication()->find('doctrine:database:drop');
        $arguments = [
            '--force' => true,
        ];
        $command->run(new ArrayInput($arguments), $output);

        // Création de la base de données
        $command = $this->getApplication()->find('doctrine:database:create');
        $arguments = [
            '--env' => 'test',
        ];
        $command->run(new ArrayInput($arguments), $output);

        // Suppression des tables
        $command = $this->getApplication()->find('doctrine:schema:update');
        $arguments = [
            '--force' => true,
        ];
        $command->run(new ArrayInput($arguments), $output);

        return Command::SUCCESS;
    }
}
