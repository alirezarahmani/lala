<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ApiHttpRequest extends ParameterBag implements ApiHttpRequestInterface
{
    /**
     * @var Request $request
     */
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }

    public function getRequest():Request
    {
        return $this->request;
    }

    public function getAttribute(string $key, $default = null)
    {
        $body = json_decode($this->request->getContent(), true);

        if (isset($body[$key])) {
            return $body[$key];
        }
        return $this->request->get($key, $default);
    }
}
