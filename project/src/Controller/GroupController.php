<?php

namespace App\Controller;

use App\Exception\GroupValidateException;
use App\Repository\GroupRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GroupService;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class GroupController extends AbstractController
{
    private GroupService $groupService;
    private Serializer $serializer;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
        $this->serializer = new Serializer([new ObjectNormalizer()],  [new JsonEncoder()]);
    }

    /**
     * @Route("/groups", methods={"POST"})
     */
    public function createAction(Request $request): Response
    {
        try {
            $request = $request->toArray();
            $this->groupService->createGroup($request['name']);
        } catch (GroupValidateException | UniqueConstraintViolationException $exception) {
            return new JsonResponse(
                ['data' => ['message' => $exception->getMessage()]],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        return new JsonResponse(
            ['data' => ['message' => 'Group is created']],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/groups", methods={"GET"})
     * @throws ExceptionInterface
     */
    public function listAction(GroupRepository $repository): Response
    {
        $groups = [];
        foreach ($repository->findAll() as $group) {
            $groups[] = $this->serializer->normalize($group,'json');
        }
        return new JsonResponse(['data' => $groups]);
    }

    /**
     * @Route("/groups/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, int $id): Response
    {
        try {
            $request = $request->toArray();
            $this->groupService->updateGroup($id, $request['name']);
        } catch (EntityNotFoundException | UniqueConstraintViolationException $exception) {
            return new JsonResponse(
                [
                    'data' => [
                        'message' => $exception->getMessage(),
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        return new JsonResponse(['data' => ['message' => 'Group is updated']]);
    }

    /**
     * @Route("/groups/{id}", methods={"DELETE"})
     */
    public function deleteAction(int $id): Response
    {
        try {
            $this->groupService->deleteGroup($id);
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse(
                [
                    'data' => [
                        'message' => $exception->getMessage(),
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        return new JsonResponse(['data' => ['message' => 'Group is deleted']]);
    }
}
