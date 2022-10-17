<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entities;

use App\Entity\EmailTemplate;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use TypeError;

class EmailTemplateTest extends KernelTestCase
{
    public function getEntity(): EmailTemplate
    {
        return (new EmailTemplate())
            ->setLabel('Template 1')
            ->setCode('TEMP_A')
            ->setSubject('ALPHA zeta MeGA')
            ->setBody('<html> {{test}} </html>')
        ;
    }

    public function assertHasErrors(EmailTemplate $emailTemplate, int $number = 0)
    {
        self::bootKernel();

        $errors = static::getContainer()->get('validator')->validate($emailTemplate);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath().' => '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidLabelEntity()
    {
        $this->assertHasErrors($this->getEntity()->setLabel(''), 2);
        $this->assertHasErrors($this->getEntity()->setLabel('Abc'), 1);
    }

    public function testExceptionLabelEntity()
    {
        $this->expectException(TypeError::class);
        $this->getEntity()->setLabel(null);
    }

    public function testInvalidCodeEntity()
    {
        $this->assertHasErrors($this->getEntity()->setCode(0), 1);
        $this->assertHasErrors($this->getEntity()->setCode(123), 1);
        $this->assertHasErrors($this->getEntity()->setCode('123'), 1);
    }

    public function testExceptionCodeEntity()
    {
        $this->expectException(TypeError::class);
        $this->getEntity()->setCode(null);
    }

    public function testInvalidSubjectEntity()
    {
        $this->assertHasErrors($this->getEntity()->setSubject(''), 2);
        $this->assertHasErrors($this->getEntity()->setSubject('Abc'), 1);
    }

    public function testExceptionSubjectEntity()
    {
        $this->expectException(TypeError::class);
        $this->getEntity()->setSubject(null);
    }

    public function testInvalidBodyEntity()
    {
        $this->assertHasErrors($this->getEntity()->setBody(''), 2);
        $this->assertHasErrors($this->getEntity()->setBody('Abc'), 1);
    }

    public function testExceptionBodyEntity()
    {
        $this->expectException(TypeError::class);
        $this->getEntity()->setBody(null);
    }

}
