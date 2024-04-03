<?php

namespace App\Healthcheck\Business\Verifier;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

readonly class HealthcheckVerifier
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Test environment variables
     *
     * @return bool
     */
    public function testEnvVariables(): bool
    {
        // Check if all required environment variables are set
        $requiredEnvVariables = [
            'APP_ENV',
            'APP_SECRET',
            'DATABASE_URL'
        ];

        foreach ($requiredEnvVariables as $envVariable) {
            if (!getenv($envVariable)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Test filesystem permissions
     *
     * @return bool
     */
    public function testFilesystemPermissions(): bool
    {
        // Check if we have read/write permissions in the project root, `var` and `logs` directories
        $directories = [
            __DIR__ . '/../../',
            __DIR__ . '/../../var',
            __DIR__ . '/../../logs'
        ];

        foreach ($directories as $directory) {
            if (!is_readable($directory) || !is_writable($directory)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Test database connection
     *
     * @return bool
     */
    public function testDatabaseConnection(): bool
    {
        // Check if database is connected
        try {
            $result = $this->entityManager->getConnection()->executeQuery('SELECT 1');

            if ($result->fetchOne() !== 1) {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}