<?php declare(strict_types=1);

namespace App\Tests\Functional\Context;

use App\Tests\Functional\Page\LoginPage;
use Behat\Behat\Context\Context;

class LoginContext implements Context
{
    private LoginPage $loginPage;

    public function __construct(LoginPage $loginPage)
    {
        $this->loginPage = $loginPage;
    }

    /**
     * @Given I am on the login page
     */
    public function IAmOnTheLoginPage()
    {
        $this->loginPage->open();
    }

    /**
     * @When I log in with my username :username and my password :password
     */
    public function connexion($username, $password)
    {
        $this->loginPage->login($username, $password);
    }

    /**
     * @Given I am logged with :username
     */
    public function IAmLoggedWith($username)
    {
        $this->loginPage->login($username, $username);
    }
}