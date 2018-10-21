<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Model\Order;
use App\Domain\Model\OrderId;
use App\Domain\OrderRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class DoctrineOrderRepository  extends EntityRepository implements OrderRepositoryInterface
{

    /**
     * Finds a Order using given id.
     *
     * @param OrderId $trackingId Id
     *
     * @return Order|object if found, else null
     */
    public function get(OrderId $trackingId): ?Order
    {
        return $this->find($trackingId);
    }

    /**
     * @param int             $page
     * @param int             $limit
     *
     * @return Pagerfanta
     */
    public function getAll(int $page = 0, int $limit = 10): Pagerfanta
    {
        $qb = $this->createQueryBuilder('o')->select('o');
        $paginator = new Pagerfanta(new DoctrineORMAdapter($qb->getQuery()));
        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);
        return $paginator;
    }

    /**
     * @param Order $order
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(Order $order): void
    {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush($order);
    }

    /**
     * @return OrderId
     * @throws \Exception
     */
    public function getNextTrackingId(): OrderId
    {
        return OrderId::generate();
    }
}
