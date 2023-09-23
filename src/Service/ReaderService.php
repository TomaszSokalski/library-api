<?php

namespace App\Service;

use App\Entity\Reader;
use App\Repository\ReaderRepository;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Uid\Uuid;

class ReaderService
{
    public function __construct(private readonly ReaderRepository $readerRepository)
    {
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


    public function create(array $data): Reader
    {
        $reader = new Reader();

        $reader->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email']);

        $this->readerRepository->add($reader, true);

        return $reader;
    }

    public function update(Uuid $id, array $data): void
    {
        $reader = $this->find($id);

        $reader->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email']);

        $this->readerRepository->add($reader, true);
    }

    public function delete(Uuid $id): void
    {
        $reader = $this->find($id);

        $this->readerRepository->remove($reader, true);
    }
}
