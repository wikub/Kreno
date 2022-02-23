<?php

namespace App\Entity;

use App\Repository\CommitmentLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=CommitmentLogRepository::class)
 */
class CommitmentLog
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $nbTimeslot;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=1, options={"default" : 0})
     */
    private $nbHour;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commitmentLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $reference = [];

    public function __construct()
    {
        $this->nbTimeslot = 0;
        $this->nbHour = 0;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbTimeslot(): ?int
    {
        return $this->nbTimeslot;
    }

    public function setNbTimeslot(int $nbTimeslot): self
    {
        $this->nbTimeslot = $nbTimeslot;

        return $this;
    }

    public function getNbHour(): ?string
    {
        return $this->nbHour;
    }

    public function setNbHour(string $nbHour): self
    {
        $this->nbHour = $nbHour;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReference(): ?array
    {
        return $this->reference;
    }

    public function setReference(?array $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
    
}
