<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception\Service\Notification;

use App\Exception\WarningException;

class CycleNotFoundException extends WarningException
{
    protected $message = 'Cycle not found.';
}
