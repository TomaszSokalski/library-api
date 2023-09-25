<?php

namespace App\Service;

use App\Entity\Reader;
use App\Repository\BookRepository;
use App\Repository\ReaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReaderService
{
    public function __construct(
        private readonly ReaderRepository $readerRepository,
        private readonly BookRepository $bookRepository,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function find(Uuid $id): Reader
    {
        $reader = $this->readerRepository->find($id);

        if (!$reader) {
            throw new ResourceNotFoundException('Reader with id ' . $id . ' does not exist');
        }

        return $reader;
    }

    /**
     * @return Reader[]
     */
    public function findAll(): array
    {
        return $this->readerRepository->findAll();
    }

    /**
     * @throws Exception
     * @param array<string> $data
     */
    public function create(array $data): Reader
    {
        try {
            $reader = new Reader();

            $this->bookPayload($reader, $data);

            return $reader;
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
            $reader = $this->find($id);

            $this->bookPayload($reader, $data);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function delete(Uuid $id): void
    {
        $reader = $this->find($id);

        $this->readerRepository->remove($reader, true);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function borrowBook(string $bookId, Uuid $id): void
    {
        $book = $this->bookRepository->find($bookId);
        $reader = $this->readerRepository->find($id);

        try {
            $this->entityManager->getConnection()->beginTransaction();

            if (count($reader->getBorrowedBooks()) >= 3) {
                throw new Exception('Reader has too many borrowed books');
            }

            if ($book->getStatus() === 'unavailable') {
                throw new Exception('Book status is unavailable');
            }

            $book->setStatus('unavailable');
            $reader->addBorrowedBook($book);

            $this->entityManager->persist($book);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param Reader $reader
     * @param array<string> $data
     * @return void
     * @throws Exception
     */
    private function bookPayload(Reader $reader, array $data): void
    {
        $reader->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email']);

        $errors = $this->validator->validate($reader);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string)$errors);
        }

        $this->readerRepository->add($reader, true);
    }
}
