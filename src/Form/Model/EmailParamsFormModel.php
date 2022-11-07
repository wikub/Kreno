<?php

namespace App\Form\Model;

class EmailParamsFormModel
{
    public ?bool $EMAIL_NOTIF_START_CYCLE_ENABLE = null;
    public ?int $EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE = null;
    public ?bool $EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE = null;
    public ?int $EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE = null;

}