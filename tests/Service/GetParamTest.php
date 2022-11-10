<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller;

use App\Entity\Param;
use App\Repository\ParamRepository;
use App\Service\GetParam;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GetParamTest extends TestCase
{
    use ProphecyTrait;

    public function testGetParam()
    {
        $param = (new Param())->setCode('PARAM_1')->setValue('Test');

        $paramRepository = $this->prophesize(ParamRepository::class);
        $paramRepository->findOneBy(['code' => 'PARAM_1'])->shouldBeCalled()->willReturn($param);

        $getParam = new GetParam($paramRepository->reveal());

        $this->assertSame(
            'Test',
            $getParam->get('PARAM_1')
        );
    }
}
