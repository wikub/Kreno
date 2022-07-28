<?php declare(strict_types=1);

namespace App\Tests\Functional\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class IndexPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'index_index';
    }
}