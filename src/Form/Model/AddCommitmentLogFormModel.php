<?php

namespace App\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;

class AddCommitmentLogFormModel
{
    public string $comment;

    public int $nbHour;

    public int $nbTimeslot;

    public ArrayCollection $users;

}