<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/users')]
class UserController extends AbstractController
{
    public function __construct(private UserService $userService) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->userService->findAll();
        return $this->json(array_map(fn(User $u) => $this->serialize($u), $users));
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        return $this->json($this->serialize($user));
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'JSON inválido'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userService->create($data);
            return $this->json($this->serialize($user), Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['errors' => json_decode($e->getMessage(), true)], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(User $user, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'JSON inválido'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userService->update($user, $data);
            return $this->json($this->serialize($user));
        } catch (\InvalidArgumentException $e) {
            return $this->json(['errors' => json_decode($e->getMessage(), true)], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->userService->delete($user);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    private function serialize(User $user): array
    {
        return [
            'id'       => $user->getId(),
            'nombre'   => $user->getNombre(),
            'apellido' => $user->getApellido(),
            'correo'   => $user->getCorreo(),
        ];
    }
}
