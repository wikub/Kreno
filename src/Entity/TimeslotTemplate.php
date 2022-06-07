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

use App\Repository\TimeslotTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TimeslotTemplateRepository::class)
 */
class TimeslotTemplate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayWeek;

    public static $dayWeekLabel = [
        1 => 'Lundi',
        2 => 'Mardi',
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi',
        7 => 'Dimanche',
    ];

    /**
     * @ORM\Column(type="time_immutable")
     */
    private $start;

    /**
     * @ORM\Column(type="time_immutable")
     */
    private $finish;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=TimeslotType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $timeslotType;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbJob;

    /**
     * @ORM\ManyToOne(targetEntity=WeekTemplate::class, inversedBy="timeslotTemplates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $weekTemplate;

    /**
     * @ORM\OneToMany(targetEntity=CommitmentContractRegularTimeslot::class, mappedBy="timeslotTemplate", orphanRemoval=true)
     */
    private $regularCommitmentContracts;

    /**
     * @ORM\OneToMany(targetEntity=Timeslot::class, mappedBy="template", orphanRemoval=true)
     */
    private $timeslots;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled;

    public function __construct()
    {
        $this->regularCommitmentContracts = new ArrayCollection();
        $this->timeslots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDayWeek(): ?int
    {
        return $this->dayWeek;
    }

    public function getDayWeekLabel(): ?string
    {
        if (!\array_key_exists($this->dayWeek, self::$dayWeekLabel)) {
            return '';
        }

        return self::$dayWeekLabel[$this->dayWeek];
    }

    public function setDayWeek(int $dayWeek): self
    {
        $this->dayWeek = $dayWeek;

        return $this;
    }

    public function getStart(): ?\DateTimeImmutable
    {
        return $this->start;
    }

    public function setStart(\DateTimeImmutable $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getFinish(): ?\DateTimeImmutable
    {
        return $this->finish;
    }

    public function setFinish(\DateTimeImmutable $finish): self
    {
        $this->finish = $finish;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTimeslotType(): ?TimeslotType
    {
        return $this->timeslotType;
    }

    public function setTimeslotType(?TimeslotType $timeslotType): self
    {
        $this->timeslotType = $timeslotType;

        return $this;
    }

    public function getNbJob(): ?int
    {
        return $this->nbJob;
    }

    public function setNbJob(int $nbJob): self
    {
        $this->nbJob = $nbJob;

        return $this;
    }

    public function getWeekTemplate(): ?WeekTemplate
    {
        return $this->weekTemplate;
    }

    public function setWeekTemplate(?WeekTemplate $weekTemplate): self
    {
        $this->weekTemplate = $weekTemplate;

        return $this;
    }

    /**
     * @return Collection|CommitmentContractRegularTimeslot[]
     */
    public function getRegularCommitmentContracts(): Collection
    {
        return $this->regularCommitmentContracts;
    }

    public function addRegularCommitmentContract(CommitmentContractRegularTimeslot $regularCommitmentContract): self
    {
        if (!$this->regularCommitmentContracts->contains($regularCommitmentContract)) {
            $this->regularCommitmentContracts[] = $regularCommitmentContract;
            $regularCommitmentContract->setTimeslotTemplate($this);
        }

        return $this;
    }

    public function removeRegularCommitmentContract(CommitmentContractRegularTimeslot $regularCommitmentContract): self
    {
        if ($this->regularCommitmentContracts->removeElement($regularCommitmentContract)) {
            // set the owning side to null (unless already changed)
            if ($regularCommitmentContract->getTimeslotTemplate() === $this) {
                $regularCommitmentContract->setTimeslotTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Timeslot[]
     */
    public function getTimeslots(): Collection
    {
        return $this->timeslots;
    }

    public function addTimeslot(Timeslot $timeslot): self
    {
        if (!$this->timeslots->contains($timeslot)) {
            $this->timeslots[] = $timeslot;
            $timeslot->setTemplate($this);
        }

        return $this;
    }

    public function removeTimeslot(Timeslot $timeslot): self
    {
        if ($this->timeslots->removeElement($timeslot)) {
            // set the owning side to null (unless already changed)
            if ($timeslot->getTemplate() === $this) {
                $timeslot->setTemplate(null);
            }
        }

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
