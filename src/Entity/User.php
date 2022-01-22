<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
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
     * @ORM\Column(type="integer")
     */
    private $category;

    private static $categoryLabel = [
        0 => 'Aucune',
        1 => 'Coordo',
        2 => 'Bénéficiaire'
    ];

    /**
     * @ORM\Column(type="integer")
     */
    private $subscriptionType;

    private static $subscriptionTypeLabel = [
        0 => 'Aucun',
        1 => 'Régulier',
        2 => 'Volant'
    ];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $enable;

    private static $enableLabel = [
        0 => 'Desactivé',
        1 => 'Activé'
    ];

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

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function getCategoryLabel(): ?string
    {
        if( array_key_exists($this->category, self::$categoryLabel) )
            return self::$categoryLabel[$this->category];
        
        return '';
    }

    public static function getCategoryLabels(): ?array
    {
        return self::$categoryLabel;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSubscriptionType(): ?int
    {
        return $this->subscriptionType;
    }

    public function getSubscriptionTypeLabel(): ?string
    {
        if( array_key_exists($this->subscriptionType, self::$subscriptionTypeLabel) )
            return self::$subscriptionTypeLabel[$this->subscriptionType];
        
        return '';
    }

    public static function getSubscriptionTypeLabels(): ?array
    {
        return self::$subscriptionTypeLabel;
    }

    public function setSubscriptionType(int $subscriptionType): self
    {
        $this->subscriptionType = $subscriptionType;

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

    public function getEnable(): ?int
    {
        return $this->enable;
    }

    public function getEnableLabel(): ?string
    {
        if( array_key_exists($this->enable, self::$enableLabel) )
            return self::$enableLabel[$this->enable];
        
        return '';
    }

    public static function getEnableLabels(): ?array
    {
        return self::$enableLabel;
    }

    public function setEnable(int $enable): self
    {
        $this->enable = $enable;

        return $this;
    }
}
