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

use App\Tests\Behat\Page\IndexPage;
use Behat\Behat\Context\Context;

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
