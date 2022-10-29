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

use App\Entity\Param;
use App\Repository\ParamRepository;
use App\Tests\FixturesTrait;
use App\Tests\WebTestCase;

class EmailParamControllerTest extends WebTestCase
{
    use FixturesTrait;

    private ParamRepository $repository;
    private string $path = '/admin/email/param/';

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = static::getContainer()->get('doctrine')->getRepository(Param::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }

        $users = $this->loadFixtures(['users']);
        $this->login($users['user1']);
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        $this->assertStringContainsString('Paramètrages des notifications par email', $crawler->filter('h1.h2')->text());
    }

    public function testIndexWithPreviousParams(): void
    {
        $param1 = (new Param())
            ->setCode('EMAIL_NOTIF_START_CYCLE_ENABLE')
            ->setValue(true);
        $this->repository->add($param1, true);

        $param2 = (new Param())
            ->setCode('EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE')
            ->setValue(true);
        $this->repository->add($param2, true);

        $param3 = (new Param())
            ->setCode('EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOUR_BEFORE')
            ->setValue(48);
        $this->repository->add($param3, true);

        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        $this->assertStringContainsString('Paramètrages des notifications par email', $crawler->filter('h1.h2')->text());

        $inputs = $crawler->selectButton('Enregistrer')->form()->getValues();

        $this->assertEquals(1, $inputs['email_params[EMAIL_NOTIF_START_CYCLE_ENABLE]']);
        $this->assertEquals(1, $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE]']);
        $this->assertEquals(48, $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOUR_BEFORE]']);

    }

    public function testIndexUpdateValues(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        $form = $crawler->selectButton('Enregistrer')->form();

        $inputs = [];
        $inputs['email_params[EMAIL_NOTIF_START_CYCLE_ENABLE]'] = 1;
        $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE]'] = 1;
        $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOUR_BEFORE]'] = 72;

        $this->client->submitForm('Enregistrer', $inputs);

        self::assertResponseStatusCodeSame(200);

        $param1 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_START_CYCLE_ENABLE'] );
        $this->assertEquals(1, $param1->getValue());

        $param2 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE'] );
        $this->assertEquals(1, $param2->getValue());

        $param3 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOUR_BEFORE'] );
        $this->assertEquals(72, $param3->getValue());
    }

}
