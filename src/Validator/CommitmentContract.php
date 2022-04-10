<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CommitmentContract extends Constraint
{
    public $message = 'Le début de l\'engagement se trouve déjà dans un contrat exixtant';
    public $mode = 'strict'; // If the constraint has configuration options, define them as public properties

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
