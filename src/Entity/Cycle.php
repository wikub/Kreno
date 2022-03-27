<?php

namespace App\Entity;

use App\Repository\CycleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CycleRepository::class)
 */
class Cycle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Week::class, mappedBy="cycle")
     */
    private $weeks;

    /**
     * @ORM\Column(type="date")
     */
    private $start;

    /**
     * @ORM\Column(type="date")
     */
    private $finish;

    public function __construct()
    {
        $this->weeks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Week>
     */
    public function getWeeks(): Collection
    {
        return $this->weeks;
    }

    public function addWeek(Week $week): self
    {
        if (!$this->weeks->contains($week)) {
            $this->weeks[] = $week;
            $week->setCycle($this);
        }

        return $this;
    }

    public function removeWeek(Week $week): self
    {
        if ($this->weeks->removeElement($week)) {
            // set the owning side to null (unless already changed)
            if ($week->getCycle() === $this) {
                $week->setCycle(null);
            }
        }

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
}
