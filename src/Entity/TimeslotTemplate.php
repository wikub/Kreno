<?php

namespace App\Entity;

use App\Repository\TimeslotTemplateRepository;
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
        7 => 'Dimanche'
    ];

    /**
     * @ORM\Column(type="time")
     */
    private $start;

    /**
     * @ORM\Column(type="time")
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
        if( !key_exists($this->dayWeek, self::$dayWeekLabel) ) return '';

        return self::$dayWeekLabel[$this->dayWeek];
    }

    public function setDayWeek(int $dayWeek): self
    {
        $this->dayWeek = $dayWeek;

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

    public function setFinish(\DateTimeInterface $finish): self
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
}
