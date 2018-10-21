<?php
declare(strict_types=1);
namespace App\Infrastructure\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiSuccessJsonResponse extends JsonResponse implements ApiResponseInterface
{
    /**
     * @param array $data
     * @param int   $code
     *
     * @return JsonResponse
     */
    public function __construct(array $data = ['status' => 'SUCCESS'], $code = Response::HTTP_OK)
    {
        parent::__construct(
            $data,
            $code
        );
        return $this->send();
    }
}
