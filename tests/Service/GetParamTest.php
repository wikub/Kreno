<?php

namespace App\Tests\Controller;

use App\Entity\Param;
use App\Repository\ParamRepository;
use App\Service\GetParam;
use Prophecy\PhpUnit\ProphecyTrait;
use PHPUnit\Framework\TestCase;

class GetParamTest extends TestCase
{
    use ProphecyTrait;

    public function testGetParam()
    {
        $param = (new Param())->setCode('PARAM_1')->setValue('Test');

        $paramRepository = $this->prophesize(ParamRepository::class);
        $paramRepository->findOneBy(['code' => 'PARAM_1'])->shouldBeCalled()->willReturn($param);
        
        $getParam = new GetParam($paramRepository->reveal());
        
        $this->assertEquals(
            'Test',
            $getParam->get('PARAM_1')
        );

    }
}