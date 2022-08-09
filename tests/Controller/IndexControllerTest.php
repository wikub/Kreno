<?php

namespace App\Tests\Controller;

use App\Tests\FixturesTrait;
use App\Tests\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

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
        $this->assertStringContainsString('Identification', $crawler->filter('div.card-header')->text());
        //$this->assertResponseRedirects('/login');
    }

    public function testIndex(): void
    {
        $users = $this->loadFixtures(['users']);
        
        $this->login($users['user1']);
        $crawler = $this->client->request('GET', '/');

        $this->assertStringContainsString('Bienvenue sur', $crawler->filter('h1.h2')->text());
    }



}