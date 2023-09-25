<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationSerivce
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ValidatorInterface $validator,
        private readonly UserPasswordHasherInterface $hasher
    ) {
    }

    /**
     * @throws Exception
     */
    public function register(array $data): void
    {
        try {
            $user = new User();

            if (null === $data['password'] || '' === $data['password']) {
                throw new Exception('Password not valid', Response::HTTP_BAD_REQUEST);
            }

            $hashedPassword = $this->hasher->hashPassword($user, $data['password']);

            $user->setUserName($data['username'])
                ->setEmail($data['email'])
                ->setPassword($hashedPassword);

            $errors = $this->validator->validate($user);
            if (count($errors) > 0) {
                throw new BadRequestHttpException((string)$errors);
            }

            $this->userRepository->add($user, true);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
