<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests;

use Fidry\AliceDataFixtures\LoaderInterface;

trait FixturesTrait
{
    private $fixtures;
    private $databaseTool;

    /**
     * Charge une série de fixture en base de donnée et ajoute les entités à l'EntityManager.
     *
     * @param array<string> $fixtures
     *
     * @return array<string,object>
     */
    public function loadFixtures(array $fixtures): array
    {
        $fixturePath = $this->getFixturesPath();
        $files = array_map(fn ($fixture) => $fixturePath.'/'.$fixture.'.yaml', $fixtures);
        /** @var LoaderInterface $loader */
        $loader = static::getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');

        return $loader->load($files);
    }

    public function getFixturesPath()
    {
        return __DIR__.'/fixtures/';
    }
}
