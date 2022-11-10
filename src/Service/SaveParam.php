<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Param;
use App\Repository\ParamRepository;

class SaveParam
{
    private ParamRepository $paramRepository;

    public function __construct(ParamRepository $paramRepository)
    {
        $this->paramRepository = $paramRepository;
    }

    public function __invoke(string $code, ?string $value): void
    {
        if (!($param = $this->loadParamByCode($code))) {
            $param = new Param();
        }

        $param
            ->setCode($code)
            ->setValue($value);

        $this->paramRepository->add($param, true);
    }

    private function loadParamByCode(string $code): ?Param
    {
        return $this->paramRepository->findOneBy(['code' => $code]);
    }
}
