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
     * @ThenI fill the add commitment credit form with
     */
    public function IFillTheAddCommitmentCreditFormWith($data)
    {
        $this->commitmentLogIndexPage->goToTheAddForm($data);
    }

    /**
     * @Then I should be  on the commitment credits monitoring page
     */
    public function iShouldBeOnTheCommitmentCreditsMonitoringPage()
    {
        throw new PendingException();
    }

    /**
     * @Then the last commiment credit is for Doe John must have :arg2 hours with the label :arg1
     */
    public function theLastCommimentCreditIsForDoeJohnMustHaveHoursWithTheLabel($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then fill the add commitment credit form form with:
     */
    public function fillTheAddCommitmentCreditFormFormWith()
    {
        throw new PendingException();
    }

    /**
     * @Then the last commiment credit is for :arg1 must have :arg3 timeslot with the label :arg2
     */
    public function theLastCommimentCreditIsForMustHaveTimeslotWithTheLabel($arg1, $arg2, $arg3)
    {
        throw new PendingException();
    }
}
