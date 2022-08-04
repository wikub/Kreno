<?php declare(strict_types=1);

namespace App\Tests\Behat\Page\Admin;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class CommitmentLogAddPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'admin_commitment_log_add';
    }

    public function fillTheForm(string $label, int $numberHour, int $numberTimeslot, string $cooperators)
    {
        $this->open();
        $this->getDocument()->fillField('label', $label);
        $this->getDocument()->fillField('nb_hour', $numberHour);
        $this->getDocument()->fillField('nb_timeslot', $numberTimeslot);
        $usersField = $this->getDocument()->findField('users');
        $xpath = $usersField->getXpath();
        $driver = $this->getSession()->getDriver();
        
        $cooperators = explode(',', $cooperators);
        foreach( $cooperators as $user) 
        {
            $usersField->setValue($user);
            $usersField->focus();
            $driver->wait(1000, true);
            
            $driver->keyDown($xpath, 40);
            $driver->keyUp($xpath, 40);
            $driver->wait(1000, true);
        }
        $this->getDocument()->pressButton('Ajouter');
    }
}