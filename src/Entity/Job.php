<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    /**
     * @ORM\Column(type="boolean")
     */
    private $manager;

    /**
     * @ORM\OneToOne(targetEntity=CommitmentLog::class, cascade={"persist", "remove"})
     */
    private $commitmentLog;

    public function __construct()
    {
        $this->manager = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isManager(): ?bool
    {
        return $this->manager;
    }

    public function setManager(bool $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getCommitmentLog(): ?CommitmentLog
    {
        return $this->commitmentLog;
    }

    public function setCommitmentLog(?CommitmentLog $commitmentLog): self
    {
        $this->commitmentLog = $commitmentLog;

        return $this;
    }
}
