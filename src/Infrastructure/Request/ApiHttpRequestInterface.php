<?php
declare(strict_types=1);
namespace App\Infrastructure\Request;

use Symfony\Component\HttpFoundation\Request;

interface ApiHttpRequestInterface
{
    public function getRequest():Request;
    public function getAttribute(string $key, $default = null);
}
