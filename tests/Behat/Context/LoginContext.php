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

namespace App\Tests\Behat\Context;

use App\Tests\Behat\Page\LoginPage;
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
