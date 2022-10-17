<?php 
namespace App\Tests\Service;

use App\Entity\EmailTemplate;
use App\Entity\Param;
use App\Repository\EmailTemplateRepository;
use App\Service\EmailSender;
use App\Service\GetParam;
use App\Tests\FixturesTrait;
use App\Tests\WebTestCase;
use Doctrine\Bundle\DoctrineBundle\Registry;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Mailer\MailerInterface;
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

        $test = $this->loadFixtures(['params', 'emailTemplates', 'users']);
        
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
        dd($emailContent);

        $this->assertEmailHtmlBodyContains($emailContent, 'Hello world');
        $this->assertEmailTextBodyContains($emailContent, 'Hello world');
    }

    public function testSendWithEmailTemplate()
    {
        $this->assertSame(true, true);
    }

}