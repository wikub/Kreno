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

use App\Repository\CommitmentContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommitmentContractRepository::class)
 */
class CommitmentContract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CommitmentType::class, inversedBy="commitmentContracts", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commitmentContracts", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=CommitmentContractRegularTimeslot::class, mappedBy="commitmentContract", orphanRemoval=true, cascade={"all"})
     */
    private $regularTimeslots;

    /**
     * @ORM\ManyToOne(targetEntity=Cycle::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $startCycle;

    /**
     * @ORM\ManyToOne(targetEntity=Cycle::class)
     */
    private $finishCycle;

    public function __construct()
    {
        $this->regularTimeslots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getFinish(): ?\DateTimeInterface
    {
        return $this->finish;
    }

    public function setFinish(?\DateTimeInterface $finish): self
    {
        $this->finish = $finish;

        return $this;
    }

    public function getType(): ?CommitmentType
    {
        return $this->type;
    }

    public function setType(?CommitmentType $type): self
    {
        $this->type = $type;

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

    /**
     * @return Collection|CommitmentContractRegularTimeslot[]
     */
    public function getRegularTimeslots(): Collection
    {
        return $this->regularTimeslots;
    }

    public function addRegularTimeslot(CommitmentContractRegularTimeslot $regularTimeslot): self
    {
        if (!$this->regularTimeslots->contains($regularTimeslot)) {
            $this->regularTimeslots[] = $regularTimeslot;
            $regularTimeslot->setCommitmentContract($this);
        }

        return $this;
    }

    public function removeRegularTimeslot(CommitmentContractRegularTimeslot $regularTimeslot): self
    {
        if ($this->regularTimeslots->removeElement($regularTimeslot)) {
            // set the owning side to null (unless already changed)
            if ($regularTimeslot->getCommitmentContract() === $this) {
                $regularTimeslot->setCommitmentContract(null);
            }
        }

        return $this;
    }

    public function getStartCycle(): ?Cycle
    {
        return $this->startCycle;
    }

    public function setStartCycle(?Cycle $startCycle): self
    {
        $this->startCycle = $startCycle;

        return $this;
    }

    public function getFinishCycle(): ?Cycle
    {
        return $this->finishCycle;
    }

    public function setFinishCycle(?Cycle $finishCycle): self
    {
        $this->finishCycle = $finishCycle;

        return $this;
    }
}
