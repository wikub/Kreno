<?php

namespace App\Entity;

use App\Repository\TimeslotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TimeslotRepository::class)
 */
class Timeslot
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
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $finish;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity=TimeslotType::class, inversedBy="timeslots")
     */
    private $timeslotType;

    /**
     * @ORM\OneToMany(targetEntity=Job::class, mappedBy="timeslot", orphanRemoval=true, cascade={"persist"})
     */
    private $jobs;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentValidation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="timeslotsValidation")
     */
    private $userValidation;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $validationAt;

    /**
     * @ORM\Column(type="array")
     */
    private $status = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="timeslotsManager")
     */
    private $manager;

    public function __construct()
    {
        $this->enabled = true;
        $this->jobs = new ArrayCollection();
        $this->status = ['draft'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDisplayName(): string
    {
        return $this->start->format('d/m/Y h:i').' '.$this->finish->format('d/m/Y h:i').' '.$this->timeslotType->getName();
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

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

    /**
     * @return Collection|Job[]
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setTimeslot($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getTimeslot() === $this) {
                $job->setTimeslot(null);
            }
        }

        return $this;
    }

    public function getCommentValidation(): ?string
    {
        return $this->commentValidation;
    }

    public function setCommentValidation(?string $commentValidation): self
    {
        $this->commentValidation = $commentValidation;

        return $this;
    }

    public function getUserValidation(): ?User
    {
        return $this->userValidation;
    }

    public function setUserValidation(?User $userValidation): self
    {
        $this->userValidation = $userValidation;

        return $this;
    }

    public function getValidationAt(): ?\DateTimeImmutable
    {
        return $this->validationAt;
    }

    public function setValidationAt(?\DateTimeImmutable $validationAt): self
    {
        $this->validationAt = $validationAt;

        return $this;
    }

    public function getStatus(): ?array
    {
        return $this->status;
    }

    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }
}
