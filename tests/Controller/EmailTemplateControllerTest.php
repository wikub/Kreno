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
use App\Repository\EmailTemplateRepository;
use App\Tests\FixturesTrait;
use App\Tests\WebTestCase;

class EmailTemplateControllerTest extends WebTestCase
{
    use FixturesTrait;

    private EmailTemplateRepository $repository;
    private string $path = '/admin/email-template/';
    private array $ref;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = static::getContainer()->get('doctrine')->getRepository(EmailTemplate::class);
        
        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }

        $this->ref = $this->loadFixtures(['users', 'params']);
        $this->login($this->ref['user1']);
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Template email');

        $this->assertStringContainsString('Template email', $crawler->filter('h1.h2')->text());
    }

    /**
    * @dataProvider provideValidEmailTemplatesToCreate
    */
    public function testCreateValid(EmailTemplate $emailTemplate): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Enregistrer', [
            'email_template[label]' => $emailTemplate->getLabel(),
            'email_template[code]' => $emailTemplate->getCode(),
            'email_template[subject]' => $emailTemplate->getSubject(),
            'email_template[body]' => $emailTemplate->getBody(),
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
        
        $paramSaved = $this->repository->findOneBy(['code' => $emailTemplate->getCode()] );

        self::assertSame($emailTemplate->getLabel(), $paramSaved->getLabel());
        self::assertSame($emailTemplate->getCode(), $paramSaved->getCode());
        self::assertSame($emailTemplate->getSubject(), $paramSaved->getSubject());
        self::assertSame($emailTemplate->getBody(), $paramSaved->getBody());
    }

    public function provideValidEmailTemplatesToCreate(): array
    {
        return [
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Template 2 - possible test') ->setCode('CODE2ABC')->setSubject('Title of the good {{name}}')->setBody('<html>Bonjour {{ name }}</html>'),
            ],
        ];
    }

    public function testUniqueCode(): void
    {
        $emailTemplate1 = (new EmailTemplate())
            ->setLabel('ABCD')
            ->setCode('CODE_1')
            ->setSubject('Title')
            ->setBody('Body email')
            ;
        
        $emailTemplate2 = (new EmailTemplate())
            ->setLabel('ABCD')
            ->setCode('CODE_2')
            ->setSubject('Title')
            ->setBody('Body email')
            ;
        
        $emailTemplate3 = (new EmailTemplate())
            ->setLabel('ABCD')
            ->setCode('CODE_2')
            ->setSubject('Title')
            ->setBody('Body email')
            ;
        
        
        $this->repository->add($emailTemplate1, true);

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Enregistrer', [
            'email_template[label]' => $emailTemplate2->getLabel(),
            'email_template[code]' => $emailTemplate2->getCode(),
            'email_template[subject]' => $emailTemplate2->getSubject(),
            'email_template[body]' => $emailTemplate2->getBody(),
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(2, $this->repository->count([]));
        
        //Param 3 - doublon
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Enregistrer', [
            'email_template[label]' => $emailTemplate3->getLabel(),
            'email_template[code]' => $emailTemplate3->getCode(),
            'email_template[subject]' => $emailTemplate3->getSubject(),
            'email_template[body]' => $emailTemplate3->getBody(),
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorExists('.is-invalid label[for="email_template_code"]');

        self::assertSame(2, $this->repository->count([]));
        
    }

    
    /**
    * @dataProvider provideInvalidEmailTemplateToCreate
    */
    public function testCreateInvalid(EmailTemplate $emailTemplate): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Enregistrer', [
            'email_template[label]' => $emailTemplate->getLabel(),
            'email_template[code]' => $emailTemplate->getCode(),
            'email_template[subject]' => $emailTemplate->getSubject(),
            'email_template[body]' => $emailTemplate->getBody(),
        ]);
        
        self::assertResponseStatusCodeSame(422);
        
        self::assertSame(0, $this->repository->count([]));
    }

    public function provideInvalidEmailTemplateToCreate(): array
    {
        return [
            [
                (new EmailTemplate())->setLabel('') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Correct label') ->setCode('')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Correct label') ->setCode('Correct_code')->setSubject('')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Correct label') ->setCode('Correct_code')->setSubject('with title')->setBody(''),
            ],
        ];
    }

    /**
    * @dataProvider provideValidEmailTemplateToEdit
    */
    public function testEditValid(EmailTemplate $emailTemplateIn, EmailTemplate $emailTemplateOut): void
    {
        $this->repository->add($emailTemplateIn, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $emailTemplateIn->getId()));

        $this->client->submitForm('Enregistrer', [
            'email_template[label]' => $emailTemplateOut->getLabel(),
            'email_template[code]' => $emailTemplateOut->getCode(),
            'email_template[subject]' => $emailTemplateOut->getSubject(),
            'email_template[body]' => $emailTemplateOut->getBody(),
        ]);

        self::assertResponseRedirects($this->path);

        $emailTemplate = $this->repository->findOneById($emailTemplateIn->getId());

        self::assertSame($emailTemplateOut->getLabel(), $emailTemplate->getLabel());
        self::assertSame($emailTemplateOut->getCode(), $emailTemplate->getCode());
        self::assertSame($emailTemplateOut->getSubject(), $emailTemplate->getSubject());
        self::assertSame($emailTemplateOut->getBody(), $emailTemplate->getBody());
    }


    public function provideValidEmailTemplateToEdit(): array
    {
        return [
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
                (new EmailTemplate())->setLabel('Template 1 updated') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1_UPDATED')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer who was update')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }} and me</html>'),
            ],
        ];
    }

    /**
    * @dataProvider provideInvalidEmailTemplatesToEdit
    */
    public function testEditInvalid(EmailTemplate $emailTemplateIn, EmailTemplate $emailTemplateOut): void
    {
        $this->repository->add($emailTemplateIn, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $emailTemplateIn->getId()));

        $this->client->submitForm('Enregistrer', [
            'email_template[label]' => $emailTemplateOut->getLabel(),
            'email_template[code]' => $emailTemplateOut->getCode(),
            'email_template[subject]' => $emailTemplateOut->getSubject(),
            'email_template[body]' => $emailTemplateOut->getBody(),
        ]);

        $emailTemplate = $this->repository->findOneById($emailTemplateIn->getId());

        //Check if Param don't change
        self::assertSame($emailTemplateIn->getLabel(), $emailTemplate->getLabel());
        self::assertSame($emailTemplateIn->getCode(), $emailTemplate->getCode());
        self::assertSame($emailTemplateIn->getSubject(), $emailTemplate->getSubject());
        self::assertSame($emailTemplateIn->getBody(), $emailTemplate->getBody());
    }

    public function provideInvalidEmailTemplatesToEdit(): array
    {
        return [
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
                (new EmailTemplate())->setLabel('Tem') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE 1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
                (new EmailTemplate())->setLabel('Template 1') ->setCode('COD')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('ABC')->setBody('<html>Bonjour {{ alpha }}</html>'),
            ],
            [
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('<html>Bonjour {{ alpha }}</html>'),
                (new EmailTemplate())->setLabel('Template 1') ->setCode('CODE_1')->setSubject('Title of the killer')->setBody('Abc'),
            ],
        ];
    }

    public function testRemove(): void
    {
        $fixture = (new EmailTemplate())
            ->setLabel('template to delete')
            ->setCode('CODE_TO_DELETE')
            ->setSubject('email')
            ->setBody('body')
            ;
        
        $this->repository->add($fixture, true);
        
        $this->client->request('GET', sprintf($this->path));
        $this->assertResponseStatusCodeSame(200);
        $this->client->submitForm('Supprimer');

        self::assertResponseRedirects($this->path);
        self::assertSame(0, $this->repository->count([]));
    }

}
