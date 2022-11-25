<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Service\Notification;

use App\Entity\Cycle;
use App\Entity\EmailTemplate;
use App\Exception\Service\Notification\CycleNotFoundException;
use App\Exception\Service\Notification\IsDisableException;
use App\Exception\Service\Notification\ParameterNotValidException;
use App\Repository\CycleRepository;
use App\Repository\EmailTemplateRepository;
use App\Service\Notification\CycleStartNotification;
use App\Service\SaveParam;
use App\Tests\FixturesTrait;
use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;

class ReminderTimeslotNotificationTest extends WebTestCase
{
    use FixturesTrait;
    use MailerAssertionsTrait;

    private CycleStartNotification $cycleStartNotification;
    private SaveParam $saveParam;
    private \DateTimeImmutable $today;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(['params_email_sender', 'emailTemplates', 'users']);
        $this->saveParam = static::getContainer()->get(SaveParam::class);

        $this->today = new \DateTimeImmutable();
    }

    public function testSend()
    {
        $this->loadContext();

        $this->getCycleStartNotificationService()->send();

        $this->assertEmailCount(5);
    }

    public function testSendWithIsDisableException()
    {
        $this->loadContext(false, 7, true, true);

        $this->expectException(IsDisableException::class);
        $this->getCycleStartNotificationService()->send();
    }

    public function testSendWithParameterNotValidException()
    {
        $this->loadContext(true, 0, true, true);

        $this->expectException(ParameterNotValidException::class);
        $this->getCycleStartNotificationService()->send();
    }

    public function testSendWithEmailTemplae()
    {
        $this->loadContext(true, 7, false, true);

        $this->expectException(\Exception::class);
        $this->getCycleStartNotificationService()->send();
    }

    public function testSendWithCycleNotFoundException()
    {
        $this->loadContext(true, 7, true, false);

        $this->expectException(CycleNotFoundException::class);
        $this->getCycleStartNotificationService()->send();
    }

    private function getCycleStartNotificationService(): CycleStartNotification
    {
        return static::getContainer()->get(CycleStartNotification::class);
    }

    private function loadContext(
        bool $paramEmailNotifStartCycleEnable = true,
        int $paramNotifStartCycleNbDaysBefore = 7,
        bool $loadFakeEmailStartCycleTemplate = true,
        bool $loadFakeCycle = true
    ) {
        ($this->saveParam)('EMAIL_NOTIF_START_CYCLE_ENABLE', $paramEmailNotifStartCycleEnable);
        ($this->saveParam)('EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE', $paramNotifStartCycleNbDaysBefore);

        if ($loadFakeEmailStartCycleTemplate) {
            $this->loadFakeEmailStartCycleTemplate();
        }

        if ($loadFakeCycle) {
            $this->loadFakeCycle(7);
        }
    }

    private function loadFakeCycle(int $starInXDays = 0): Cycle
    {
        $start = $this->today->modify('+'.$starInXDays.' days');
        $finish = $start->modify('+4 weeks');

        $cycle = (new Cycle())
            ->setStart($start)
            ->setFinish($finish);

        $this->getCyleRepository()->add($cycle, true);

        return $cycle;
    }

    private function loadFakeEmailStartCycleTemplate(): void
    {
        $template = (new EmailTemplate())
            ->setCode('EMAIL_NOTIF_START_CYCLE')
            ->setLabel('Template Label')
            ->setSubject('Template subject')
            ->setBody('Template Body');

        $this->getEmailTemplateRepository()->add($template, true);
    }

    private function loadFakeTimeslotJobRegistration()
    {
    }

    private function getCyleRepository(): CycleRepository
    {
        return static::getContainer()->get('doctrine')->getRepository(Cycle::class);
    }

    private function getEmailTemplateRepository(): EmailTemplateRepository
    {
        return static::getContainer()->get('doctrine')->getRepository(EmailTemplate::class);
    }
}
