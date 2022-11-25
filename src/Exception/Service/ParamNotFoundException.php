<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception\Service;

class ParamNotFoundException extends \Exception
{
    protected $parameter;
    protected $templateMessage = 'The parameter %s was not found.';

    public function __construct(string $parameter)
    {
        $this->parameter = $parameter;
        $this->message = sprintf($this->templateMessage, $parameter);
    }
}
