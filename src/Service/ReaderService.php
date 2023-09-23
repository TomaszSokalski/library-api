<?php

namespace App\Service;

use App\Entity\Reader;
use App\Repository\ReaderRepository;
use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReaderService
{
    public function __construct(
        private readonly ReaderRepository $readerRepository,
        private readonly ValidatorInterface $validator
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
