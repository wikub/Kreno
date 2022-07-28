<?php declare(strict_types=1);

namespace App\Tests\Functional\Context;

use Behat\Behat\Context\Context;
use App\Tests\Functional\Page\IndexPage;

class IndexContext implements Context
{

    private IndexPage $indexPage;

    public function __construct(IndexPage $indexPage)
    {
        $this->indexPage = $indexPage;
    }

    /**
     * @Then je dois être sur ma page d'accueil
     */
    public function jeDoisEtreSurMaPageDaccueil()
    {
        $this->indexPage->verify();
    }
}