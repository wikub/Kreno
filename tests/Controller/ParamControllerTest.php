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
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

use App\Tests\FixturesTrait;
use App\Tests\WebTestCase;

class ParamControllerTest extends WebTestCase
{
    use FixturesTrait;

    private ParamRepository $repository;
    private string $path = '/admin/param/';

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
        self::assertPageTitleContains('Paramétrages');

        $this->assertStringContainsString('Paramètrages', $crawler->filter('h1.h2')->text());
    }

    /**
    * @dataProvider provideValidParamsToEdit
    */
    public function testCreateValid(Param $param): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Enregistrer', [
            'param[code]' => $param->getCode(),
            'param[value]' => $param->getValue(),
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
        
        $paramSaved = $this->repository->findOneBy(['code' => $param->getCode()] );

        self::assertSame($param->getCode(), $paramSaved->getCode());
        self::assertSame($param->getValue(), $paramSaved->getValue());
    }

    public function provideValidParamsToCreate(): array
    {
        return [
            [
                (new Param())->setCode('CODE1')->setValue('VALUE1'),
            ],
            [
                (new Param())->setCode('CODE_2')->setValue('VALUE 2'),
            ],
            [
                (new Param())->setCode('CODE4')->setValue(null),
            ],
        ];
    }

    public function testUniqueCode(): void
    {
        $param1 = (new Param())
            ->setCode('CODE_1');
        
        $param2 = (new Param())
            ->setCode('CODE_2');
        
        $param3 = (new Param())
            ->setCode('CODE_2');
        
        
        $this->repository->add($param1, true);

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Enregistrer', [
            'param[code]' => $param2->getCode(),
            'param[value]' => $param2->getValue(),
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(2, $this->repository->count([]));
        
        //Param 3 - doublon
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Enregistrer', [
            'param[code]' => $param3->getCode(),
            'param[value]' => $param3->getValue(),
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorExists('.is-invalid label[for="param_code"]');

        self::assertSame(2, $this->repository->count([]));
        
    }

    
    /**
    * @dataProvider provideInvalidParamsToCreate
    */
    public function testCreateInvalid(Param $param): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Enregistrer', [
            'param[code]' => $param->getCode(),
            'param[value]' => $param->getValue(),
        ]);
        
        self::assertResponseStatusCodeSame(422);
        self::assertSelectorExists('.is-invalid label[for="param_code"]');

        self::assertSame(0, $this->repository->count([]));
    }

    public function provideInvalidParamsToCreate(): array
    {
        return [
            [
                (new Param())->setCode('COD')->setValue('VALUE1'),
            ],
            [
                (new Param())->setCode('CODE 2')->setValue('VALUE 2'),
            ],
        ];
    }

    /**
    * @dataProvider provideValidParamsToEdit
    */
    public function testEditValid(Param $paramIn, Param $paramOut): void
    {
        $this->repository->add($paramIn, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $paramIn->getId()));

        $this->client->submitForm('Enregistrer', [
            'param[code]' => $paramOut->getCode(),
            'param[value]' => $paramOut->getValue(),
        ]);

        self::assertResponseRedirects($this->path);

        $param = $this->repository->findOneById($paramIn->getId());

        self::assertSame($paramOut->getCode(), $param->getCode());
        self::assertSame($paramOut->getValue(), $param->getValue());
    }


    public function provideValidParamsToEdit(): array
    {
        return [
            [
                (new Param())->setCode('CODE1')->setValue('VALUE1'),
                (new Param())->setCode('COD1')->setValue('VALUE1'),
            ],
            [
                (new Param())->setCode('CODE2')->setValue('VALUE2'),
                (new Param())->setCode('CODE_2')->setValue('VALUE 2'),
            ],
            [
                (new Param())->setCode('CODE4')->setValue('VALUE 4'),
                (new Param())->setCode('CODE4')->setValue(null),
            ],
        ];
    }

    /**
    * @dataProvider provideInvalidParamsToEdit
    */
    public function testEditInvalid(Param $paramIn, Param $paramOut): void
    {
        $this->repository->add($paramIn, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $paramIn->getId()));

        $this->client->submitForm('Enregistrer', [
            'param[code]' => $paramOut->getCode(),
            'param[value]' => $paramOut->getValue(),
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorExists('.is-invalid label[for="param_code"]');

        $param = $this->repository->findOneById($paramIn->getId());

        //Check if Param don't change
        self::assertSame($paramIn->getCode(), $param->getCode());
        self::assertSame($paramIn->getValue(), $param->getValue());
    }

    public function provideInvalidParamsToEdit(): array
    {
        return [
            [
                (new Param())->setCode('CODE2')->setValue('VALUE 2'),
                (new Param())->setCode('COD')->setValue('VALUE 2'),
            ],
            [
                (new Param())->setCode('CODE4')->setValue('VALUE 4'),
                (new Param())->setCode('CODE 4')->setValue('VALUE 4'),
            ],
        ];
    }

    public function testRemove(): void
    {
        $fixture = new Param();
        $fixture->setCode('CODE_TO_DELETE');
        $fixture->setValue('My Title');

        $this->repository->add($fixture, true);
        
        $this->client->request('GET', sprintf($this->path));
        $this->client->submitForm('Supprimer');

        self::assertResponseRedirects($this->path);
        self::assertSame(0, $this->repository->count([]));
    }

}
