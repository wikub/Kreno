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

class ParamController extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIndex(): void
    {
        $users = $this->loadFixtures(['users']);
        $this->login($users['user1']);

        $crawler = $this->client->request('GET', '/admin/param');

        $this->assertStringContainsString('Paramètrages', $crawler->filter('h1.h2')->text());
    }

    public function testCreateValidNewParam(): void
    {
        $crawler = $this->client->request('GET', '/admin/param/new');
        $this->assertStringContainsString('Nouveau paramètrage', $crawler->filter('h1.h2')->text());

        $form =$crawler->selectButton('Enregistrer')->form([
            'code'  => 'test_code_valid',
            'value' => 'test value',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/param');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testCreateValidNewParamWithNullValue(): void
    {
        $crawler = $this->client->request('GET', '/admin/param/new');
        $this->assertStringContainsString('Nouveau paramètrage', $crawler->filter('h1.h2')->text());

        $form =$crawler->selectButton('Enregistrer')->form([
            'code'  => 'test_code_null',
            'value' => null,
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/param');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testCreateNewParamWithInvalidCode(): void
    {
        $crawler = $this->client->request('GET', '/admin/param/new');
        $this->assertStringContainsString('Nouveau paramètrage', $crawler->filter('h1.h2')->text());

        $form =$crawler->selectButton('Enregistrer')->form([
            'code'  => 'tes',
            'value' => 'test value',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/param/new');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testEditParam1(): void
    {
        $crawler = $this->client->request('GET', '/admin/param/edit/1');
        $this->assertStringContainsString('Modification du paramètrage #1', $crawler->filter('h1.h2')->text());

        $form =$crawler->selectButton('Enregistrer')->form([
            'code'  => 'test_code_edit',
            'value' => 'test value',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/param');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testVizualizeParam(): void
    {

    }
}
