<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private ValidatorInterface $validator
    ) {}

    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function findOne(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function create(array $data): User
    {
        $user = new User();
        $user->setNombre($data['nombre'] ?? '');
        $user->setApellido($data['apellido'] ?? '');
        $user->setCorreo($data['correo'] ?? '');
        $user->setContrasena(password_hash($data['contrasena'] ?? '', PASSWORD_BCRYPT));

        $this->validate($user);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['nombre']))     $user->setNombre($data['nombre']);
        if (isset($data['apellido']))   $user->setApellido($data['apellido']);
        if (isset($data['correo']))     $user->setCorreo($data['correo']);
        if (isset($data['contrasena'])) $user->setContrasena(password_hash($data['contrasena'], PASSWORD_BCRYPT));

        $this->validate($user);

        $this->em->flush();

        return $user;
    }

    public function delete(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    private function validate(User $user): void
    {
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new \InvalidArgumentException(json_encode($messages));
        }
    }
}
