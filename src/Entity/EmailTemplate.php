<?php

namespace App\Entity;

use App\Repository\EmailTemplateRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailTemplateRepository::class)
 */
class EmailTemplate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "Le code doit avoir au minimum {{ limit }} caractères",
     *      maxMessage = "Le code ne peut avoir que {{ limit }} caractères maximums"
     * )
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "Le code doit avoir au minimum {{ limit }} caractères",
     *      maxMessage = "Le code ne peut avoir que {{ limit }} caractères maximums"
     * )
     * @Assert\Regex("/^[a-zA-Z0-9_]*$/", message="Le code doit contenir des caractères alphanumériques sans espace, seul l'underscore est autorisé.")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "Le code doit avoir au minimum {{ limit }} caractères",
     *      maxMessage = "Le code ne peut avoir que {{ limit }} caractères maximums"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      minMessage = "Le code doit avoir au minimum {{ limit }} caractères",
     * )
     */
    private $body;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}