<?php

namespace App\Entity;

use App\Repository\ParamRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParamRepository::class)
 * @UniqueEntity("code")
 */
class Param
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
