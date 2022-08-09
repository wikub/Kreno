<?php declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use App\Tests\Behat\Page\IndexPage;

class IndexContext implements Context
{

    private IndexPage $indexPage;

    public function __construct(IndexPage $indexPage)
    {
        $this->indexPage = $indexPage;
    }

    /**
     * @Then I must be on my homepage
     */
    public function jeDoisEtreSurMaPageDaccueil()
    {
        $this->indexPage->verify();
    }
}