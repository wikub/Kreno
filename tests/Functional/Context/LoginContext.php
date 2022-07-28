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
     * @Given je suis sur la page de connexion
     */
    public function jeSuisSurLaPageDeConnexion()
    {
        $this->loginPage->open();
    }

    /**
     * @When /^je me connecte en tant que "([^"]+)" avec le mot de passe "([^"]+)"$/
     */
    public function connexion($email, $password)
    {
        $this->loginPage->login($email, $password);
    }
}