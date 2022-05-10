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

use App\Repository\WeekRepository;
use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=WeekRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="start_idx", columns={"start_at"})})
 */
class Week
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $weekType;

    public static $weekTypeLabel = [
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
    ];

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $startAt;

    /**
     * @ORM\OneToMany(targetEntity=Timeslot::class, mappedBy="week", orphanRemoval=true)
     * @ORM\OrderBy({"start" = "ASC"})
     */
    private $timeslots;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Cycle::class, inversedBy="weeks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cycle;

    public function __construct()
    {
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

    public function getDisplayName(): ?string
    {
        if ($this->name) {
            return $this->name;
        }

        $finishAt = $this->startAt->modify('+6 days');

        return 'Semaine '.$this->getWeekTypeLabel().' du '.$this->startAt->format('d/m').' au '.$finishAt->format('d/m Y');
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getWeekType(): ?int
    {
        return $this->weekType;
    }

    public function getWeekTypeLabel(): ?string
    {
        if (!\array_key_exists($this->weekType, self::$weekTypeLabel)) {
            return '';
        }

        return self::$weekTypeLabel[$this->weekType];
    }

    public static function getWeekTypeLabels(): array
    {
        return self::$weekTypeLabel;
    }

    public function setWeekType(int $weekType): self
    {
        $this->weekType = $weekType;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): self
    {
        $this->startAt = $startAt;

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
            $timeslot->setWeek($this);
        }

        return $this;
    }

    public function removeTimeslot(Timeslot $timeslot): self
    {
        if ($this->timeslots->removeElement($timeslot)) {
            // set the owning side to null (unless already changed)
            if ($timeslot->getWeek() === $this) {
                $timeslot->setWeek(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDays(): ArrayCollection
    {
        $curDay = $this->startAt;

        // Init dayOfWeek array
        $dayOfweek = [];
        for ($i = 1; $i <= 7; ++$i) {
            $dayOfweek[$i]['date'] = $curDay;
            $dayOfweek[$i]['timeslots'] = [];
            $curDay = $curDay->add(new DateInterval('P1D'));
        }

        // Get timeslots for each day of week
        foreach ($this->timeslots as $timeslot) {
            $nDayOfWeek = $timeslot->getStart()->format('N');
            $dayOfweek[$nDayOfWeek]['timeslots'][] = $timeslot;
        }

        return new ArrayCollection($dayOfweek);
    }

    public function getCycle(): ?Cycle
    {
        return $this->cycle;
    }

    public function setCycle(?Cycle $cycle): self
    {
        $this->cycle = $cycle;

        return $this;
    }
}
