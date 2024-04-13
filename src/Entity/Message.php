<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    /*CODE REVIEW - Nullable Annotation for $status Property: The status property is annotated as nullable 
    (#[ORM\Column(length: 255, nullable: true)]). Depending on application's requirements, this may or may not be desirable. If the status should always have a value, consider removing the nullable: true option.
    */
    #[ORM\Column(length: 255)]
    private ?string $status = null;
    
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /* CODE REVIEW - In PHP, the static return type declaration is not allowed for methods.
    In PHP, the self return type declaration is used to indicate that the method returns an instance of the current class (Message in this case). Therefore, the corrected method signature would be:
    This applies to all setters.
    */
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
}
