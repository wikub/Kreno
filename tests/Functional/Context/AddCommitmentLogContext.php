<?php declare(strict_types=1);

namespace App\Tests\Functional\Context;

use App\Tests\Functional\Page\Admin\CommitmentLogIndexPage;

use Behat\Behat\Context\Context;

class AddCommitmentLogContext implements Context
{
    private CommitmentLogIndexPage $commitmentLogIndexPage;

    public function __construct(CommitmentLogIndexPage $commitmentLogIndexPage)
    {
        $this->commitmentLogIndexPage = $commitmentLogIndexPage;
    }

    /**
     * @Given I am on the commitment credits monitoring page
     */
    public function IAmOnTheCommitmentLogPage()
    {
        $this->commitmentLogIndexPage->open();
    }

    /**
     * @When I go to the add commitment credit form
     */
    public function IGoToTheAddCommitmentCreditForm($username, $password)
    {
        $this->commitmentLogIndexPage->goToTheAddForm();
    }

    /**
     * @ThenI fill the add commitment credit form with
     */
    public function IFillTheAddCommitmentCreditFormWith($data)
    {
        $this->commitmentLogIndexPage->goToTheAddForm($data);
    }
}