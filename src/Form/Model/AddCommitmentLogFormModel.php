<?php

namespace App\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\Expression(
 *     "this.nbHour != 0 or this.nbTimeslot != 0",
 *     message="Vous devez renseigner au moins une nombre d'heure ou de créneau (négatif ou positif)"
 * )
 */
class AddCommitmentLogFormModel
{
    public string $comment;

    public int $nbHour;

    public int $nbTimeslot;

    public ArrayCollection $users;

}