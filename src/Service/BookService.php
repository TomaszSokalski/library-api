<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Uid\Uuid;

class BookService
{
    public function __construct(private readonly BookRepository $bookRepository)
    {
    }

    public function find(Uuid $id): Book
    {
        $book = $this->bookRepository->find($id);

        if (!$book) {
            throw new ResourceNotFoundException('Book with id ' . $id . ' does not exist');
        }

        return $book;
    }

    /**
     * @return Book[]
     */
    public function findAll(): array
    {
        return $this->bookRepository->findAll();
    }

    /**
     * @throws Exception
     * @param array<string> $data
     */
    public function create(array $data): Book
    {
        try {
            $book = new Book();

            $this->bookPayload($book, $data);

            return $book;
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @throws Exception
     * @param array<string> $data
     */
    public function update(Uuid $id, array $data): void
    {
        try {
            $book = $this->find($id);

            $this->bookPayload($book, $data);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function delete(Uuid $id): void
    {
        $book = $this->find($id);

        $this->bookRepository->remove($book, true);
    }

    /**
     * @param Book $book
     * @param array<string> $data
     * @return void
     * @throws Exception
     */
    private function bookPayload(Book $book, array $data): void
    {
        $book->setTitle($data['title'])
            ->setAuthor($data['author'])
            ->setPublicationDate(new \DateTimeImmutable($data['publicationDate']))
            ->setStatus($data['status']);

        $this->bookRepository->add($book, true);
    }
}
