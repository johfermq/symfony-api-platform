<?php

namespace App\Controller\Api\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class JsonExceptionResponseTransformerListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface)
        {
            $data = [
                'class' => \get_class($exception),
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
            ];

            $response = $this->prepareResponse($data, $data['code']);

            $event->setResponse($response);
        }
    }

    /**
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    private function prepareResponse(array $data, int $statusCode): JsonResponse
    {
        $response = new JsonResponse($data, $statusCode);
        $response->headers->set('Server-Time', \time());
        $response->headers->set('X-Error-Code', $statusCode);

        return $response;
    }
}