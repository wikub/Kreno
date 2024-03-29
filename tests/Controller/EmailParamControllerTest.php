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

use App\Entity\EmailTemplate;
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
            ->setCode('EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE')
            ->setValue(7);
        $this->repository->add($param2, true);

        $param3 = (new Param())
            ->setCode('EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE')
            ->setValue(true);
        $this->repository->add($param3, true);

        $param4 = (new Param())
            ->setCode('EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE')
            ->setValue(48);
        $this->repository->add($param4, true);

        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        $this->assertStringContainsString('Paramètrages des notifications par email', $crawler->filter('h1.h2')->text());

        $inputs = $crawler->selectButton('Enregistrer')->form()->getValues();

        $this->assertSame('1', $inputs['email_params[EMAIL_NOTIF_START_CYCLE_ENABLE]']);
        $this->assertSame('7', $inputs['email_params[EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE]']);
        $this->assertSame('1', $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE]']);
        $this->assertSame('48', $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE]']);
    }

    public function testIndexEnableNotificationWithoutTemplate(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        $inputs = [];
        $inputs['email_params[EMAIL_NOTIF_START_CYCLE_ENABLE]'] = 1;
        $inputs['email_params[EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE]'] = 7;
        $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE]'] = 1;
        $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE]'] = 72;

        $this->client->submitForm('Enregistrer', $inputs);

        self::assertResponseStatusCodeSame(200);

        $param1 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_START_CYCLE_ENABLE']);
        $this->assertSame('', $param1->getValue());

        $param2 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE']);
        $this->assertSame('7', $param2->getValue());

        $param3 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE']);
        $this->assertSame('', $param3->getValue());

        $param4 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE']);
        $this->assertSame('72', $param4->getValue());
    }

    public function testIndexEnableCycleNotificationWithoutNbDays(): void
    {
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        $inputs = [];
        $inputs['email_params[EMAIL_NOTIF_START_CYCLE_ENABLE]'] = 1;

        $this->client->submitForm('Enregistrer', $inputs);

        self::assertResponseStatusCodeSame(200);

        $param1 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_START_CYCLE_ENABLE']);
        $this->assertSame('', $param1->getValue());

        $param2 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE']);
        $this->assertNull($param2->getValue());

        $param3 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE']);
        $this->assertSame('', $param3->getValue());

        $param4 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE']);
        $this->assertNull($param4->getValue());
    }

    public function testIndexEnableReminderTimeslotNotificationWithoutNbHours(): void
    {
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        $inputs = [];
        $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE]'] = 1;

        $this->client->submitForm('Enregistrer', $inputs);

        self::assertResponseStatusCodeSame(200);

        $param1 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_START_CYCLE_ENABLE']);
        $this->assertSame('', $param1->getValue());

        $param2 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE']);
        $this->assertNull($param2->getValue());

        $param3 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE']);
        $this->assertSame('', $param3->getValue());

        $param4 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE']);
        $this->assertNull($param4->getValue());
    }

    public function testIndexUpdateValues(): void
    {
        $this->createFakeTemplate();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        $inputs = [];
        $inputs['email_params[EMAIL_NOTIF_START_CYCLE_ENABLE]'] = 1;
        $inputs['email_params[EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE]'] = 7;
        $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE]'] = 1;
        $inputs['email_params[EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE]'] = 72;

        $this->client->submitForm('Enregistrer', $inputs);

        self::assertResponseStatusCodeSame(200);

        $param1 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_START_CYCLE_ENABLE']);
        $this->assertSame('1', $param1->getValue());

        $param2 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE']);
        $this->assertSame('7', $param2->getValue());

        $param3 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE']);
        $this->assertSame('1', $param3->getValue());

        $param4 = $this->repository->findOneBy(['code' => 'EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE']);
        $this->assertSame('72', $param4->getValue());
    }

    private function createFakeTemplate(): void
    {
        $templateRepository = static::getContainer()->get('doctrine')->getRepository(EmailTemplate::class);

        $template1 = (new EmailTemplate())
            ->setCode('EMAIL_NOTIF_START_CYCLE')
            ->setLabel('EMAIL_NOTIF_START_CYCLE')
            ->setSubject('EMAIL_NOTIF_START_CYCLE')
            ->setBody('EMAIL_NOTIF_START_CYCLE');
        $templateRepository->add($template1, true);

        $template1 = (new EmailTemplate())
            ->setCode('EMAIL_NOTIF_REMINDER_TIMESLOT')
            ->setLabel('EMAIL_NOTIF_REMINDER_TIMESLOT')
            ->setSubject('EMAIL_NOTIF_REMINDER_TIMESLOT')
            ->setBody('EMAIL_NOTIF_REMINDER_TIMESLOT');
        $templateRepository->add($template1, true);
    }
}
