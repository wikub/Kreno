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

namespace App\Tests\Behat\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class IndexPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'index_index';
    }
}
