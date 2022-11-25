<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Service;

use App\Entity\EmailTemplate;
use App\Service\EmailSender;
use App\Tests\FixturesTrait;
use App\Tests\WebTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Mime\Address;

class EmailSenderTest extends WebTestCase
{
    use FixturesTrait;
    use MailerAssertionsTrait;
    use ProphecyTrait;

    private EmailSender $emailSender;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(['params_email_sender', 'emailTemplates', 'users']);

        $this->emailSender = static::getContainer()->get(EmailSender::class);
    }

    public function testGetEmailTemplate()
    {
        $emailTemplate = $this->emailSender->getEmailTemplate('TEST');

        $this->assertInstanceOf(EmailTemplate::class, $emailTemplate);
        $this->assertSame('Test', $emailTemplate->getLabel());
    }

    public function testSend()
    {
        $this->assertEmailCount(0);

        $this->emailSender->send(
            'emails/test.html.twig',
            'Test',
            new Address('us@me.com', 'Test'),
            ['var1' => 'Hello world']
        );

        $this->assertEmailCount(1);

        $emailContent = $this->getMailerMessage();

        $this->assertEmailHtmlBodyContains($emailContent, 'Hello world');
        $this->assertEmailTextBodyContains($emailContent, 'Hello world');
    }

    public function testSendWithEmailTemplate()
    {
        $this->assertEmailCount(0);

        $this->emailSender->sendWithEmailTemplate(
            'TEST',
            new Address('us@me.com', 'Test'),
            ['var1' => 'Hello world']
        );

        $this->assertEmailCount(1);

        $emailContent = $this->getMailerMessage();

        $this->assertEmailHtmlBodyContains($emailContent, 'Hello world');
        $this->assertEmailTextBodyContains($emailContent, 'Hello world');
    }
}
