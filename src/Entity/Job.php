<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="jobs")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Timeslot::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $timeslot;

    /**
     * @ORM\ManyToOne(targetEntity=JobDoneType::class, inversedBy="jobs")
     */
    private $jobDone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserCategory(): ?UserCategory
    {
        return $this->userCategory;
    }

    public function setUserCategory(?UserCategory $userCategory): self
    {
        $this->userCategory = $userCategory;

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

    public function getTimeslot(): ?Timeslot
    {
        return $this->timeslot;
    }

    public function setTimeslot(?Timeslot $timeslot): self
    {
        $this->timeslot = $timeslot;

        return $this;
    }

    public function getJobDone(): ?JobDoneType
    {
        return $this->jobDone;
    }

    public function setJobDone(?JobDoneType $jobDone): self
    {
        $this->jobDone = $jobDone;

        return $this;
    }
}
