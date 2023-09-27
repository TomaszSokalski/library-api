<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    private const STATUS = ['available', 'unavailable'];

    #[Assert\Uuid]
    #[OA\Property(type: 'uuid', example: '8a47fd24-34d3-4ed0-b69c-4d151bf277c6')]
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['main'])]
    private ?Uuid $id = null;

    #[Assert\NotNull]
    #[Assert\NotBlank(message: 'Book title is required')]
    #[Assert\Type('string')]
    #[OA\Property(minLength: 1, example: 'Latarnik')]
    #[ORM\Column(length: 255)]
    #[Groups(['main'])]
    private ?string $title = null;

    #[Assert\NotNull]
    #[Assert\NotBlank(message: 'Book author is required')]
    #[Assert\Type('string')]
    #[OA\Property(minLength: 1, example: 'Henryk Sienkiewicz')]
    #[ORM\Column(length: 255)]
    #[Groups(['main'])]
    private ?string $author = null;

    #[Assert\NotNull]
    #[Assert\Type('\DateTimeInterface')]
    #[OA\Property(type: 'date', example: '2009-12-25')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    #[Groups(['main'])]
    private ?\DateTimeInterface $publicationDate = null;

    #[Assert\NotNull]
    #[Assert\NotBlank(message: 'Book status is required')]
    #[Assert\Type('string')]
    #[Assert\Choice(choices: self::STATUS, message: 'Choose a valid status.')]
    #[ORM\Column(length: 255)]
    #[Groups(['main'])]
    private ?string $status = null;

    #[Ignore]
    #[ORM\ManyToMany(targetEntity: Reader::class, mappedBy: 'borrowedBooks')]
    private Collection $readers;

    public function __construct()
    {
        $this->readers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Reader>
     */
    public function getReaders(): Collection
    {
        return $this->readers;
    }

    public function addReader(Reader $reader): static
    {
        if (!$this->readers->contains($reader)) {
            $this->readers->add($reader);
            $reader->addBorrowedBook($this);
        }

        return $this;
    }

    public function removeReader(Reader $reader): static
    {
        if ($this->readers->removeElement($reader)) {
            $reader->removeBorrowedBook($this);
        }

        return $this;
    }
}
