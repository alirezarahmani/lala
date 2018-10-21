<?php

namespace App\Controller;

use App\Domain\OrderRepositoryInterface;
use App\Infrastructure\Request\ApiHttpRequestInterface;
use App\Infrastructure\Response\ApiErrorJsonResponse;
use App\Infrastructure\Response\ApiResponseInterface;
use App\Infrastructure\Response\ApiSuccessJsonResponse;
use App\Domain\Service\OrderService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Assert\Assertion;

final class OrderController
{
    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * OrderController constructor.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        opcache_reset();
        $this->orderService = $orderService;
    }

    /**
     * @param ApiHttpRequestInterface $request
     *
     * @return ApiResponseInterface
     * @Route("/order", methods={"POST"})
     */
    public function create(ApiHttpRequestInterface $request): ApiResponseInterface
    {
        try {
            $orderDetails =  $this->orderService->addNewOrder(
                $request->getAttribute('origin', []),
                $request->getAttribute('destination', [])
            );
            return new ApiSuccessJsonResponse($orderDetails);
        } catch (\InvalidArgumentException $e) {
            return new ApiErrorJsonResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string                  $id
     * @param ApiHttpRequestInterface $request
     *
     * @return ApiResponseInterface
     * @Route("/order/{id}", methods={"PUT"})
     */
    public function take(string $id, ApiHttpRequestInterface $request): ApiResponseInterface
    {
        try {
            Assertion::notEmpty($id, 'order not found', Response::HTTP_NOT_FOUND);
            $this->orderService->takeOrder($id, $request->getAttribute('status'));
            return new ApiSuccessJsonResponse();
        } catch (\InvalidArgumentException $e) {
            return new ApiErrorJsonResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param ApiHttpRequestInterface  $request
     * @param OrderRepositoryInterface $orderRepository
     *
     * @return ApiResponseInterface
     * @Route("/order", methods={"GET"})
     */
    public function all(ApiHttpRequestInterface $request, OrderRepositoryInterface $orderRepository): ApiResponseInterface
    {
        try {
            $dataResult = $this->orderService->allOrders(
                intval($request->getAttribute('page', 1)),
                intval($request->getAttribute('limit', 10))
            );
            return new ApiSuccessJsonResponse($dataResult);
        } catch (\InvalidArgumentException $e) {
            return new ApiErrorJsonResponse($e->getMessage(), $e->getCode());
        }
    }
}