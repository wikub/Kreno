<?php

namespace App\Entity;

use App\Repository\EmailLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailLogRepository::class)
 */
class EmailLog
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
    private $emailTo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $htmlContent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textContent;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $sendedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailTo(): ?string
    {
        return $this->emailTo;
    }

    public function setEmailTo(string $emailTo): self
    {
        $this->emailTo = $emailTo;

        return $this;
    }

    public function getNameTo(): ?string
    {
        return $this->nameTo;
    }

    public function setNameTo(?string $nameTo): self
    {
        $this->nameTo = $nameTo;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getHtmlContent(): ?string
    {
        return $this->htmlContent;
    }

    public function setHtmlContent(?string $htmlContent): self
    {
        $this->htmlContent = $htmlContent;

        return $this;
    }

    public function getTextContent(): ?string
    {
        return $this->textContent;
    }

    public function setTextContent(?string $textContent): self
    {
        $this->textContent = $textContent;

        return $this;
    }

    public function getSendedAt(): ?\DateTimeImmutable
    {
        return $this->sendedAt;
    }

    public function setSendedAt(\DateTimeImmutable $sendedAt): self
    {
        $this->sendedAt = $sendedAt;

        return $this;
    }
}
