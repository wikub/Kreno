<?php

namespace App\Filter;

use App\Entity\Week;

class UserWeekScheduler {

    private $week;

    public function getWeek(): ?Week
    {
        return $this->week;
    }

    public function setWeek(?Week $week): self
    {
        $this->week = $week;

        return $this;
    }
}