<?php

namespace App\Healthcheck\Communication\Controller;

use App\Healthcheck\Business\Verifier\HealthcheckVerifier;
use App\Kernel\Communication\HttpFoundation\Response\ApiResponse;
use App\Kernel\Communication\HttpFoundation\Type\StatusCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Kernel\Communication\Controller\ControllerInterface;
use Symfony\Component\Routing\Requirement\Requirement;

class HealthcheckController extends AbstractController implements ControllerInterface
{
    public function __construct(
        private readonly HealthcheckVerifier $healthcheckVerifier
    ) {
    }

    #[Route('/api/healthcheck', name: 'healthcheck', methods: ['GET'])]
    public function register(): JsonResponse
    {
        $response = new ApiResponse();

        $environment = $this->healthcheckVerifier->testEnvVariables();
        $database = $this->healthcheckVerifier->testDatabaseConnection();
        $filesystem = $this->healthcheckVerifier->testFilesystemPermissions();

        // If all tests pass, return a success message
        if ($environment && $database && $filesystem) {
            $response
                ->setStatusCode(StatusCode::OK)
                ->setMessage('All tests passed successfully.')
                ->setData([
                    'environment' => $environment,
                    'database' => $database,
                    'filesystem' => $filesystem
                ]);
        } else {
            $response
                ->setStatusCode(StatusCode::INTERNAL_ERROR)
                ->setMessage('Some tests failed.')
                ->setData([
                    'environment' => $environment,
                    'database' => $database,
                    'filesystem' => $filesystem
                ]);
        }

        return $response->render();
    }
}
