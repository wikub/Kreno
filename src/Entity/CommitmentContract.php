<?php

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
     * @ORM\Column(type="date")
     */
    private $start;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $finish;

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
     * @ORM\OneToMany(targetEntity=CommitmentContractRegularTimeslot::class, mappedBy="commitmentContract", orphanRemoval=true, cascade={"persist"})
     */
    private $regularTimeslots;

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
            $regularTimeslot->setCommitmentContrat($this);
        }

        return $this;
    }

    public function removeRegularTimeslot(CommitmentContractRegularTimeslot $regularTimeslot): self
    {
        if ($this->regularTimeslots->removeElement($regularTimeslot)) {
            // set the owning side to null (unless already changed)
            if ($regularTimeslot->getCommitmentContrat() === $this) {
                $regularTimeslot->setCommitmentContrat(null);
            }
        }

        return $this;
    }
}
