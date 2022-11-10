<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller;

use App\Tests\FixturesTrait;
use App\Tests\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIndexUnauthentidied(): void
    {
        $this->loadFixtures(['users']);

        $this->client->request('GET', '/');
        $crawler = $this->client->followRedirect();
        var_export($crawler->text());
        $this->assertStringContainsString('Identification', $crawler->filter('div.card-header')->text());
        // $this->assertResponseRedirects('/login');
    }

    public function testIndex(): void
    {
        $users = $this->loadFixtures(['users']);

        $this->login($users['user1']);
        $crawler = $this->client->request('GET', '/');

        $this->assertStringContainsString('Bienvenue sur', $crawler->filter('h1.h2')->text());
    }
}
