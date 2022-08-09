<?php declare(strict_types=1);

namespace App\Tests\Behat\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class LoginPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'login';
    }

    public function login($username, $password)
    {
        $this->open();
        $this->getDocument()->fillField('username', $username);
        $this->getDocument()->fillField('password', $password);
        $this->getDocument()->pressButton('Entrer');
    }

}