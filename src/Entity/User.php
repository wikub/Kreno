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

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /*
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $phonenumber;

    /**
     * @ORM\ManyToOne(targetEntity=UserCategory::class, inversedBy="users")
     */
    private $userCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Job::class, mappedBy="user")
     */
    private $jobs;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity=CommitmentContract::class, mappedBy="user")
     */
    private $commitmentContracts;

    /**
     * @ORM\OneToMany(targetEntity=Timeslot::class, mappedBy="userValidation")
     */
    private $timeslotsValidation;

    /**
     * @ORM\OneToMany(targetEntity=CommitmentLog::class, mappedBy="user", orphanRemoval=true)
     */
    private $commitmentLogs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $odooId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="users", cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=UserActivity::class, mappedBy="user", orphanRemoval=true)
     */
    private $activities;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->commitmentContracts = new ArrayCollection();
        $this->timeslotsValidation = new ArrayCollection();
        $this->enabled = true;
        $this->commitmentLogs = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getUserCategory(): ?UserCategory
    {
        return $this->userCategory;
    }

    public function setUserCategory(?UserCategory $userCategory): self
    {
        $this->userCategory = $userCategory;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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
    public function getPastJobs(): Collection
    {
        return $this->jobs->filter(function ($job) {
            return $job->getTimeslot()->getStart() < (new DateTime());
        });
    }

    /**
     * @return Collection|Job[]
     */
    public function getFuturJobs(): Collection
    {
        return $this->jobs->filter(function ($job) {
            return $job->getTimeslot()->getStart() >= (new DateTime());
        });
    }

    /**
     * @return Collection|Job[]
     */
    public function getFuturJobsInDays(int $nbDays = 5): Collection
    {
        return $this->jobs->filter(function ($job) use ($nbDays) {
            return $job->getTimeslot()->getStart()->format('Ymd') === (new DateTime())->modify('+'.$nbDays.' days')->format('Ymd');
        });
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setUser($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getUser() === $this) {
                $job->setUser(null);
            }
        }

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function isEnabled(): bool
    {
        return (bool) $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

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
            $commitmentContract->setUser($this);
        }

        return $this;
    }

    public function removeCommitmentContract(CommitmentContract $commitmentContract): self
    {
        if ($this->commitmentContracts->removeElement($commitmentContract)) {
            // set the owning side to null (unless already changed)
            if ($commitmentContract->getUser() === $this) {
                $commitmentContract->setUser(null);
            }
        }

        return $this;
    }

    public function getCurrentCommitmentContract(): ?CommitmentContract
    {
        $current = $this->commitmentContracts->filter(function ($contract) {
            return null === $contract->getFinishCycle();
        })->first();

        if ($current) {
            return $current;
        }

        return null;
    }

    /**
     * @return Collection|Timeslot[]
     */
    public function getTimeslotsValidation(): Collection
    {
        return $this->timeslotsValidation;
    }

    public function addTimeslotsValidation(Timeslot $timeslotsValidation): self
    {
        if (!$this->timeslotsValidation->contains($timeslotsValidation)) {
            $this->timeslotsValidation[] = $timeslotsValidation;
            $timeslotsValidation->setUserValidation($this);
        }

        return $this;
    }

    public function removeTimeslotsValidation(Timeslot $timeslotsValidation): self
    {
        if ($this->timeslotsValidation->removeElement($timeslotsValidation)) {
            // set the owning side to null (unless already changed)
            if ($timeslotsValidation->getUserValidation() === $this) {
                $timeslotsValidation->setUserValidation(null);
            }
        }

        return $this;
    }

    public function displayName(): ?string
    {
        return $this->firstname.' '.$this->name;
    }

    /**
     * @return Collection|CommitmentLog[]
     */
    public function getCommitmentLogs(): Collection
    {
        return $this->commitmentLogs;
    }

    public function addCommitmentLog(CommitmentLog $commitmentLog): self
    {
        if (!$this->commitmentLogs->contains($commitmentLog)) {
            $this->commitmentLogs[] = $commitmentLog;
            $commitmentLog->setUser($this);
        }

        return $this;
    }

    public function removeCommitmentLog(CommitmentLog $commitmentLog): self
    {
        if ($this->commitmentLogs->removeElement($commitmentLog)) {
            // set the owning side to null (unless already changed)
            if ($commitmentLog->getUser() === $this) {
                $commitmentLog->setUser(null);
            }
        }

        return $this;
    }

    public function getOdooId(): ?string
    {
        return $this->odooId;
    }

    public function setOdooId(?string $odooId): self
    {
        $this->odooId = $odooId;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, UserActivity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(UserActivity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setUser($this);
        }

        return $this;
    }

    public function removeActivity(UserActivity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getUser() === $this) {
                $activity->setUser(null);
            }
        }

        return $this;
    }
}
