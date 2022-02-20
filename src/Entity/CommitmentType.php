<?php

namespace App\Entity;

use App\Repository\CommitmentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommitmentTypeRepository::class)
 */
class CommitmentType
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=CommitmentContract::class, mappedBy="type")
     */
    private $commitmentContracts;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbTimeslotMin;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=1, nullable=true)
     */
    private $nbHourMin;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $regular;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $manager;

    public function __construct()
    {
        $this->commitmentContracts = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|CommitmentContract[]
     */
    public function getCommitmentContracts(): Collection
    {
        return $this->commitmentContracts;
    }

    public function addCommitmentContract(CommitmentContract $commitmentContract): self
    {
        if (!$this->commitmentContracts->contains($commitmentContract)) {
            $this->commitmentContracts[] = $commitmentContract;
            $commitmentContract->setType($this);
        }

        return $this;
    }

    public function removeCommitmentContract(CommitmentContract $commitmentContract): self
    {
        if ($this->commitmentContracts->removeElement($commitmentContract)) {
            // set the owning side to null (unless already changed)
            if ($commitmentContract->getType() === $this) {
                $commitmentContract->setType(null);
            }
        }

        return $this;
    }

    public function getNbTimeslotMin(): ?int
    {
        return $this->nbTimeslotMin;
    }

    public function setNbTimeslotMin(?int $nbTimeslotMin): self
    {
        $this->nbTimeslotMin = $nbTimeslotMin;

        return $this;
    }

    public function getNbHourMin(): ?string
    {
        return $this->nbHourMin;
    }

    public function setNbHourMin(?string $nbHourMin): self
    {
        $this->nbHourMin = $nbHourMin;

        return $this;
    }

    public function getRegular(): ?bool
    {
        return $this->regular;
    }

    public function setRegular(bool $regular): self
    {
        $this->regular = $regular;

        return $this;
    }

    public function getManager(): ?bool
    {
        return $this->manager;
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
}
