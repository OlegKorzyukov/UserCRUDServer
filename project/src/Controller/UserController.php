<?php

namespace App\Controller;

use App\Exception\UserValidateException;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserCRUDService;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    private UserCRUDService $userCRUDService;

    public function __construct(UserCRUDService $userCRUDService)
    {
        $this->userCRUDService = $userCRUDService;
    }

    /**
     * @Route("/users", methods={"POST"})
     */
    public function createAction(Request $request): JsonResponse
    {
        dd($request);

        try {
            $this->userCRUDService->createUser($request->request->get('name'), $request->request->get('email'));
        } catch (UserValidateException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return new JsonResponse([], Response::HTTP_CREATED);
    }

    /**
     * @Route("/users", methods={"GET"})
     */
    public function getAction(UserRepository $repository): JsonResponse
    {
        return new JsonResponse($repository->findAll());
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, int $id): JsonResponse
    {
        try {
            $this->userCRUDService->updateUser(
                $id,
                $request->request->get('name'),
                $request->request->get('email')
            );
            return new JsonResponse('User is updated');
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     */
    public function deleteAction(int $id): JsonResponse
    {
        try {
            $this->userCRUDService->deleteUser($id);
            return new JsonResponse('User is deleted');
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
