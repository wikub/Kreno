<?php

namespace App\Entity;

use App\Repository\WeekTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeekTemplateRepository::class)
 */
class WeekTemplate
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
     * @ORM\OneToMany(targetEntity=TimeslotTemplate::class, mappedBy="weekTemplate", orphanRemoval=true)
     */
    private $timeslotTemplates;

    public function __construct()
    {
        $this->timeslotTemplates = new ArrayCollection();
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

    /**
     * @return Collection|TimeslotTemplate[]
     */
    public function getTimeslotTemplates(): Collection
    {
        return $this->timeslotTemplates;
    }

    public function addTimeslotTemplate(TimeslotTemplate $timeslotTemplate): self
    {
        if (!$this->timeslotTemplates->contains($timeslotTemplate)) {
            $this->timeslotTemplates[] = $timeslotTemplate;
            $timeslotTemplate->setWeekTemplate($this);
        }

        return $this;
    }

    public function removeTimeslotTemplate(TimeslotTemplate $timeslotTemplate): self
    {
        if ($this->timeslotTemplates->removeElement($timeslotTemplate)) {
            // set the owning side to null (unless already changed)
            if ($timeslotTemplate->getWeekTemplate() === $this) {
                $timeslotTemplate->setWeekTemplate(null);
            }
        }

        return $this;
    }
}
