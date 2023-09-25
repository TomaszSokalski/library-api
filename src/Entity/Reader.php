<?php

namespace App\Entity;

use App\Repository\ReaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReaderRepository::class)]
class Reader
{
    #[Assert\Uuid]
    #[OA\Property(type: 'uuid', example: '8a47fd24-34d3-4ed0-b69c-4d151bf277c6')]
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['main'])]
    private ?Uuid $id = null;

    #[Assert\NotNull]
    #[Assert\NotBlank(message: 'Reader firstname is required')]
    #[Assert\Type('string')]
    #[OA\Property(minLength: 1, example: 'John')]
    #[ORM\Column(length: 255)]
    #[Groups(['main'])]
    private ?string $firstName = null;

    #[Assert\NotNull]
    #[Assert\NotBlank(message: 'Reader lastname is required')]
    #[Assert\Type('string')]
    #[OA\Property(minLength: 1, example: 'Doe')]
    #[ORM\Column(length: 255)]
    #[Groups(['main'])]
    private ?string $lastName = null;

    #[Assert\NotNull]
    #[Assert\NotBlank(message: 'Reader email is required')]
    #[Assert\Email]
    #[OA\Property(example: 'john@doe.com')]
    #[ORM\Column(length: 255)]
    #[Groups(['main'])]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'readers')]
    #[Groups(['main'])]
    private Collection $borrowedBooks;

    public function __construct()
    {
        $this->borrowedBooks = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBorrowedBooks(): Collection
    {
        return $this->borrowedBooks;
    }

    public function addBorrowedBook(Book $borrowedBook): static
    {
        if (!$this->borrowedBooks->contains($borrowedBook)) {
            $this->borrowedBooks->add($borrowedBook);
        }

        return $this;
    }

    public function removeBorrowedBook(Book $borrowedBook): static
    {
        $this->borrowedBooks->removeElement($borrowedBook);

        return $this;
    }
}
