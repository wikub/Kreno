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
     * @ORM\Column(type="date_immutable")
     */
    private $start;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $finish;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $applyCommimentContracts;

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

    public function getName(): string
    {
        return $this->start->format('d/m/Y').' au '.$this->finish->format('d/m/Y');
    }

    public function getApplyCommimentContracts(): ?\DateTimeInterface
    {
        return $this->applyCommimentContracts;
    }

    public function setApplyCommimentContracts(?\DateTimeInterface $applyCommimentContracts): self
    {
        $this->applyCommimentContracts = $applyCommimentContracts;

        return $this;
    }
}
