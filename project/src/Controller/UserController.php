<?php

namespace App\Controller;

use App\Exception\UserValidateException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserController extends AbstractController
{
    private UserService $userService;
    private Serializer $serializer;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->serializer = new Serializer([new ObjectNormalizer()],  [new JsonEncoder()]);
    }

    /**
     * @Route("/users", methods={"POST"})
     */
    public function createAction(Request $request): Response
    {
        try {
            $request = $request->toArray();
            $this->userService->createUser($request['name'], $request['email']);
        } catch (UserValidateException | UniqueConstraintViolationException $exception) {
            return new JsonResponse(
                ['data' => ['message' => $exception->getMessage()]],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        return new JsonResponse(
            ['data' => ['message' => 'User is created']],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/users", methods={"GET"})
     * @throws ExceptionInterface
     */
    public function listAction(UserRepository $repository): Response
    {
        $users = [];
        foreach ($repository->findAll() as $user) {
            $users[] = $this->serializer->normalize($user,'json');
        }
        return new JsonResponse(['data' => $users]);
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, int $id): Response
    {
        try {
            $request = $request->toArray();
            $this->userService->updateUser(
                $id,
                $request['name'],
                $request['email']
            );
            return new JsonResponse(['data' => ['message' => 'User is updated']]);
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse(
                [
                    'data' => [
                        'message' => $exception->getMessage(),
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     */
    public function deleteAction(int $id): Response
    {
        try {
            $this->userService->deleteUser($id);
            return new JsonResponse(['data' => ['message' => 'User is deleted']]);
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse(
                [
                    'data' => [
                        'message' => $exception->getMessage(),
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY
            );        }
    }
}
