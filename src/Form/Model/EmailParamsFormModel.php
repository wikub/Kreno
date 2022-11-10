<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Model;

class EmailParamsFormModel
{
    public ?bool $EMAIL_NOTIF_START_CYCLE_ENABLE = null;
    public ?int $EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE = null;
    public ?bool $EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE = null;
    public ?int $EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE = null;
}
