<?php

namespace App\Entity;

use App\Repository\EntityRelationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntityRelationRepository::class)]
class EntityRelation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $ownerEntity = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $ownerEntityId = null;

    #[ORM\Column(length: 100)]
    private ?string $relatedEntity = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?int $relatedEntityId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnerEntity(): ?string
    {
        return $this->ownerEntity;
    }

    public function setOwnerEntity(string $ownerEntity): static
    {
        $this->ownerEntity = $ownerEntity;

        return $this;
    }

    public function getOwnerEntityId(): ?string
    {
        return $this->ownerEntityId;
    }

    public function setOwnerEntityId(string $ownerEntityId): static
    {
        $this->ownerEntityId = $ownerEntityId;

        return $this;
    }

    public function getRelatedEntity(): ?string
    {
        return $this->relatedEntity;
    }

    public function setRelatedEntity(string $relatedEntity): static
    {
        $this->relatedEntity = $relatedEntity;

        return $this;
    }

    public function getRelatedEntityId(): ?int
    {
        return $this->relatedEntityId;
    }

    public function setRelatedEntityId(int $relatedEntityId): static
    {
        $this->relatedEntityId = $relatedEntityId;

        return $this;
    }
}
