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

namespace App\Tests\Behat;

use App\Tests\Behat\Page\Admin\CommitmentLogIndexPage;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

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
     * @Then I should be  on the commitment credits monitoring page
     */
    public function iShouldBeOnTheCommitmentCreditsMonitoringPage()
    {
        $this->commitmentLogIndexPage->verify();
    }

    /**
     * @Then I should see the last commiment credit is for :name must have :unit :type with the label :label
     */
    public function theLastCommimentCreditIsForDoeJohnMustHaveHoursWithTheLabel($name, $unit, $type, $label)
    {
        $this->commitmentLogIndexPage->iShouldSeeRowInTheTable($name, $unit, $type, $label);
    }

}
