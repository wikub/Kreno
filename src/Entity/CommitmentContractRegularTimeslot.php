<?php

namespace App\Entity;

use App\Repository\CommitmentContractRegularTimeslotRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommitmentContractTimeslotTemplateRepository::class)
 */
class CommitmentContractRegularTimeslot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CommitmentContract::class, inversedBy="regularTimeslots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commitmentContract;

    /**
     * @ORM\ManyToOne(targetEntity=TimeslotTemplate::class, inversedBy="regularCommitmentContracts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $timeslotTemplate;

    /**
     * @ORM\Column(type="date")
     */
    private $start;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $finish;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommitmentContract(): ?CommitmentContract
    {
        return $this->commitmentContract;
    }

    public function setCommitmentContract(?CommitmentContract $commitmentContract): self
    {
        $this->commitmentContract = $commitmentContract;

        return $this;
    }

    public function getTimeslotTemplate(): ?TimeslotTemplate
    {
        return $this->timeslotTemplate;
    }

    public function setTimeslotTemplate(?TimeslotTemplate $timeslotTemplate): self
    {
        $this->timeslotTemplate = $timeslotTemplate;

        return $this;
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
}
