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

use App\Repository\TimeslotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TimeslotRepository::class)
 */
class Timeslot
{
    /*
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;
    public const STATUS_DRAFT_VALUE = 1;
    public const STATUS_DRAFT_TEXT = 'Brouillon';
    public const STATUS_OPEN_VALUE = 2;
    public const STATUS_OPEN_TEXT = 'Ouvert';
    public const STATUS_VALID_VALUE = 3;
    public const STATUS_VALID_TEXT = 'Validé';
    public const STATUS_CLOSE_VALUE = 4;
    public const STATUS_CLOSE_TEXT = 'Fermé';

    public const STATUS_OPEN_SUBSCRIB_VALUE = 10;
    public const STATUS_OPEN_SUBSCRIB_TEXT = 'Inscription ouverte';
    public const STATUS_OPEN_DONE_VALUE = 11;
    public const STATUS_OPEN_DONE_TEXT = 'Réalisé';

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
     * @ORM\OneToMany(targetEntity=Job::class, mappedBy="timeslot", fetch="EAGER", cascade={"persist", "remove"}, orphanRemoval=true)
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
     * @ORM\ManyToOne(targetEntity=Week::class, inversedBy="timeslots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $week;

    /**
     * @ORM\ManyToOne(targetEntity=TimeslotTemplate::class, inversedBy="timeslots")
     */
    private $template;

    public function __construct()
    {
        $this->enabled = true;
        $this->jobs = new ArrayCollection();
        // $this->status = ['draft'];
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
        if ($this->name) {
            return $this->name;
        }

        return $this->getTimeslotType()->getName();
    }

    public function getDisplayDateInterval(): string
    {
        $text = $this->start->format('d/m/Y').' de '.$this->start->format('H:i');
        if ($this->start->format('Ymd') === $this->finish->format('Ymd')) {
            $text .= ' à '.$this->finish->format('H:i');
        } else {
            $text = ' au '.$this->start->format('d/m/Y').' à '.$this->start->format('H:i');
        }

        return $text;
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

    /**
     * @return Collection|Job[]
     */
    public function getFreeManagerJobs(): Collection
    {
        return $this->jobs->filter(function ($job) {
            return null === $job->getUser() && true === $job->isManager();
        });
    }

    /**
     * @return Collection|Job[]
     */
    public function getFreeNoManagerJobs(): Collection
    {
        return $this->jobs->filter(function ($job) {
            return null === $job->getUser() && false === $job->isManager();
        });
    }

    public function getManagerJobs(): Collection
    {
        return $this->jobs->filter(function ($job) {
            return true === $job->isManager();
        });
    }

    public function getNoManagerJobs(): Collection
    {
        return $this->jobs->filter(function ($job) {
            return false === $job->isManager();
        });
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
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

    public function getUserJobs(User $user): Collection
    {
        return $this->getJobs()->filter(function ($job) use ($user) {
            return $job->getUser() === $user;
        });
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

    public function isOpen(): bool
    {
        if (\array_key_exists('open', $this->status)) {
            return true;
        }

        return false;
    }

    public function isClosed(): bool
    {
        if (\array_key_exists('closed', $this->status)) {
            return true;
        }

        return false;
    }

    public function isValidated(): bool
    {
        if (\array_key_exists('validated', $this->status)) {
            return true;
        }

        return false;
    }

    public function isCommitmentLogged(): bool
    {
        if (\array_key_exists('commitment_logged', $this->status)) {
            return true;
        }

        return false;
    }

    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getWeek(): ?Week
    {
        return $this->week;
    }

    public function setWeek(?Week $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getTemplate(): ?TimeslotTemplate
    {
        return $this->template;
    }

    public function setTemplate(?TimeslotTemplate $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function isSubscribable(): bool
    {
        if ($this->start <= (new \DateTime())) {
            return false;
        }

        return true;
    }

    public function isUnsubscribable(): bool
    {
        if ($this->start <= (new \DateTime())->modify('+2 days')) {
            return false;
        }

        return true;
    }
}
