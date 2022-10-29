<?php

namespace App\Service;

use App\Entity\Param;
use App\Repository\ParamRepository;

class GetParam
{
    private ParamRepository $paramRepository;

    public function __construct(ParamRepository $paramRepository)
    {
        $this->paramRepository = $paramRepository;
    }

    public function get(string $code): ?string
    {
        $param = $this->paramRepository->findOneBy(['code' => $code]);

        if( !isset($param) ) return null;

        return $param->getValue();
    }
}