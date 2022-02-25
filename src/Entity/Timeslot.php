<?php

namespace App\Entity;

use App\Repository\TimeslotRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=TimeslotRepository::class)
 */
class Timeslot
{
    const STATUS_DRAFT_VALUE = 1;
    const STATUS_DRAFT_TEXT = 'Brouillon';
    const STATUS_OPEN_VALUE = 2;
    const STATUS_OPEN_TEXT = 'Ouvert';
    const STATUS_VALID_VALUE = 3;
    const STATUS_VALID_TEXT = 'Validé';
    const STATUS_CLOSE_VALUE = 4;
    const STATUS_CLOSE_TEXT = 'Fermé';

    const STATUS_OPEN_SUBSCRIB_VALUE = 10;
    const STATUS_OPEN_SUBSCRIB_TEXT = 'Inscription ouverte';
    const STATUS_OPEN_DONE_VALUE = 11;
    const STATUS_OPEN_DONE_TEXT = 'Réalisé';
    
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

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
        //$this->status = ['draft'];
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
        if( $this->name ) return $this->name;

        return $this->getTimeslotType()->getName();
    }

    public function getDisplayDateInterval(): string
    {
        $text = $this->start->format('d/m/Y').' de '.$this->start->format('H:i');
        if( $this->start->format('Ymd') == $this->finish->format('Ymd') ) {
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
        return $this->jobs->filter(function($job) {
            return ($job->getUser() == null && $job->isManager() == true );
        });
    }

    /**
     * @return Collection|Job[]
     */
    public function getFreeNoManagerJobs(): Collection
    {
        return $this->jobs->filter(function($job) {
            return ($job->getUser() == null && $job->isManager() == false );
        });
    }

    public function getManagerJobs(): Collection
    {
        return $this->jobs->filter(function($job){
            return $job->isManager() == true;
        });
    }

    public function getNoManagerJobs(): Collection
    {
        return $this->jobs->filter(function($job){
            return $job->isManager() == false;
        });
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

    public function isOpen(): bool
    {
        if( key_exists('open', $this->status) ) return true;
        return false;
    }

    public function isClosed(): bool
    {
        if( key_exists('closed', $this->status) ) return true;
        return false;
    }

    public function isValidated(): bool
    {
        if( key_exists('validated', $this->status) ) return true;
        if( key_exists('commitment_logged', $this->status) ) return true;
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



}
