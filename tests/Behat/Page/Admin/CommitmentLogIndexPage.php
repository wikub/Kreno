<?php declare(strict_types=1);

namespace App\Tests\Behat\Page\Admin;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class CommitmentLogIndexPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'admin_commitment_log_index';
    }

    public function goToTheAddForm()
    {
        $this->open();
        $this->getDocument()->pressButton('Ajouter');
    }
}