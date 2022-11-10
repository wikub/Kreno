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

        if (!isset($param)) {
            return null;
        }

        return $param->getValue();
    }
}
