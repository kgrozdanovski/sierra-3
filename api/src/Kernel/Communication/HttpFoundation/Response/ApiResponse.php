<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Communication\HttpFoundation\Response;

use App\Kernel\Communication\HttpFoundation\Type\ResponseProperty;
use App\Kernel\Communication\HttpFoundation\Type\StatusCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * The HTTP status code to be returned.
     *
     * @var StatusCode
     */
    private StatusCode $statusCode;

    /**
     * The value of the 'message' key in the response body.
     *
     * @var string
     */
    private string $message;

    /**
     * The value of the 'data' key in the response body.
     *
     * @var array
     */
    private array $data;

    public function __construct()
    {
        $this->statusCode = StatusCode::OK;
        $this->data = [];
    }

    /**
     * Determines the structure and forms a JSON response to return to the HTTP client.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        $body = [
            ResponseProperty::MESSAGE->value => $this->message ?? $this->getDefaultMessage($this->statusCode),
            ResponseProperty::DATA->value => $this->data
        ];

        return new JsonResponse($body, $this->statusCode->value);
    }

    /**
     * Determine the standard (default) message associated with an HTTP status code.
     *
     * @param StatusCode $statusCode
     * @return string
     */
    private function getDefaultMessage(StatusCode $statusCode): string
    {
        return Response::$statusTexts[$statusCode->value];
    }

    /**
     * @return StatusCode
     */
    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    /**
     * @param StatusCode $statusCode
     * @return ApiResponse
     */
    public function setStatusCode(StatusCode $statusCode): ApiResponse
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return ApiResponse
     */
    public function setMessage(string $message): ApiResponse
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return ApiResponse
     */
    public function setData(array $data): ApiResponse
    {
        $this->data = $data;
        return $this;
    }
}