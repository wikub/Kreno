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

use App\Entity\Param;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TypeError;

class ParamTest extends KernelTestCase
{
    public function getEntity(): Param
    {
        return (new Param())
            ->setCode('P1_PARAM')
            ->setValue('ALPHA zeta MeGA');
    }

    public function assertHasErrors(Param $code, int $number = 0)
    {
        self::bootKernel();

        $errors = static::getContainer()->get('validator')->validate($code);
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

    public function testExceptionCodeEntity()
    {
        $this->expectException(TypeError::class);
        $this->getEntity()->setCode(null);
        
    }
    
    public function testInvalidCodeEntity()
    {
        
        $this->assertHasErrors($this->getEntity()->setCode(0), 1);
        $this->assertHasErrors($this->getEntity()->setCode(123), 1);
        $this->assertHasErrors($this->getEntity()->setCode('123'), 1);
    }
}
