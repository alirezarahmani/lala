<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiErrorJsonResponse extends JsonResponse implements ApiResponseInterface
{
    /**
     * @param string $errors
     * @param int    $code
     *
     * @return JsonResponse
     */
    public function __construct($errors,  $code)
    {
        // prevent assertion default codes
        if ($code < 400 ) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        parent::__construct(            [
                'error' =>  $errors
            ],
            $code
        );
        return $this->send();
    }
}
