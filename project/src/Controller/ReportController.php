<?php

namespace App\Controller;

use App\Service\ReportService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ReportController extends AbstractController
{
    private ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * @Route("/reports", methods={"GET"})
     */
    public function generateReportAction(Request $request): Response
    {
        //use rabbitmq
        try {
            return new BinaryFileResponse(
                new \SplFileInfo($this->reportService->generateReport()),
                Response::HTTP_OK,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename=' . ReportService::getFilename(),
                    'Content-Transfer-Encoding' => 'binary',
                    'Expires' => 0,
                    'Cache-Control' => 'must-revalidate',
                ]
            );
        } catch (Exception | ExceptionInterface $e) {
            return new JsonResponse(['data' => ['message' => "File was not found"]], Response::HTTP_NOT_FOUND);
        }
    }
}
