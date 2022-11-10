<?php

declare(strict_types=1);

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Admin;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class CommitmentLogAddPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'admin_commitment_log_add';
    }

    public function fillTheForm(string $comment, int $numberHour, int $numberTimeslot, string $users)
    {
        $this->open();
        $this->getDocument()->fillField('comment', $comment);
        $this->getDocument()->fillField('nb_hour', $numberHour);
        $this->getDocument()->fillField('nb_timeslot', $numberTimeslot);
        $usersField = $this->getDocument()->findField('users');
        $xpath = $usersField->getXpath();
        $driver = $this->getSession()->getDriver();

        $users = explode(',', $users);
        foreach ($users as $user) {
            $usersField->focus();
            $usersField->setValue($user);
            $usersField->keyPress('');
            $usersField->focus();
            $driver->wait(1000, true);

            $driver->keyDown($xpath, 40);
            $driver->keyUp($xpath, 40);
            $driver->wait(1000, true);
        }
        $this->getDocument()->pressButton('Ajouter');
    }
}
