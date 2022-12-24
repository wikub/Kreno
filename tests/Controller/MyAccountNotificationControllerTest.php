<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Test\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\FixturesTrait;
use App\Tests\WebTestCase;
use Doctrine\ORM\EntityManager;

class MyAccountNotificationControllerTest extends WebTestCase
{
    use FixturesTrait;

    private UserRepository $userRepository;
    private EntityManager $entityManager;
    private string $path = '/me';
    private array $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->userRepository = $this->entityManager->getRepository(User::class);

        $this->users = $this->loadFixtures(['users']);
        $this->login($this->users['user1']);
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        $this->assertStringContainsString('Mon profil', $crawler->filter('h1.h2')->text());
    }

    public function testDisableEmailNotificationTimeslotReminder(): void
    {
        /* @var App\Entity\User */
        $user = $this->users['user1'];

        $this->assertTrue($user->getEmailNotifTimeslotReminder());

        $crawler = $this->client->request('GET', $this->path);

        $link = $crawler->selectLink('Rappel de début de créneau')->link();

        $this->assertStringContainsString('timeslot-reminder/disable', $link->getUri());
        $this->client->click($link);
        $crawler = $this->client->followRedirect();

        self::assertResponseStatusCodeSame(200);

        $user = $this->userRepository->findOneBy(['id' => $user->getId()]);

        $this->assertFalse($user->getEmailNotifTimeslotReminder());
    }

    public function testEnableEmailNotificationTimeslotReminder(): void
    {
        /* @var App\Entity\User */
        $user = $this->users['user1'];

        $this->assertTrue($user->getEmailNotifTimeslotReminder());
        $user->setEmailNotifTimeslotReminder(false);
        $this->entityManager->persist($user);

        $crawler = $this->client->request('GET', $this->path);

        $link = $crawler->selectLink('Rappel de début de créneau')->link();

        $this->assertStringContainsString('timeslot-reminder/enable', $link->getUri());
        $this->client->click($link);
        $crawler = $this->client->followRedirect();

        self::assertResponseStatusCodeSame(200);

        $user = $this->userRepository->findOneBy(['id' => $user->getId()]);

        $this->assertTrue($user->getEmailNotifTimeslotReminder());
    }

    public function testDisableEmailNotificationCycleStart(): void
    {
        /* @var App\Entity\User */
        $user = $this->users['user1'];

        $this->assertTrue($user->getEmailNotifCycleStart());

        $crawler = $this->client->request('GET', $this->path);

        $link = $crawler->selectLink('Rappel de début de cycle')->link();

        $this->assertStringContainsString('cycle-start/disable', $link->getUri());
        $this->client->click($link);
        $crawler = $this->client->followRedirect();

        self::assertResponseStatusCodeSame(200);

        $user = $this->userRepository->findOneBy(['id' => $user->getId()]);

        $this->assertFalse($user->getEmailNotifCycleStart());
    }

    public function testEnableEmailNotificationCycleStart(): void
    {
        /* @var App\Entity\User */
        $user = $this->users['user1'];

        $this->assertTrue($user->getEmailNotifCycleStart());
        $user->setEmailNotifCycleStart(false);
        $this->entityManager->persist($user);

        $crawler = $this->client->request('GET', $this->path);

        $link = $crawler->selectLink('Rappel de début de cycle')->link();

        $this->assertStringContainsString('cycle-start/enable', $link->getUri());
        $this->client->click($link);
        $crawler = $this->client->followRedirect();

        self::assertResponseStatusCodeSame(200);

        $user = $this->userRepository->findOneBy(['id' => $user->getId()]);

        $this->assertTrue($user->getEmailNotifCycleStart());
    }
}
