<?php

namespace App\Tests\Model\Order;

use App\Domain\Model\OrderId;
use App\Tests\TestCase;
use Ramsey\Uuid\Uuid;

class TrackingIdTest extends TestCase
{
    /** @test */
    public function it_constructs_itself_from_string(): void
    {
        $uuid = Uuid::uuid4();
        $trackingId = OrderId::fromString($uuid->toString());

        $this->assertEquals($uuid->toString(), $trackingId->toString());
    }

    /** @test */
    public function it_returns_string_representation_of_uuid(): void
    {
        $uuid = Uuid::uuid4();
        $orderId = new OrderId($uuid);

        $this->assertEquals($uuid->toString(), $orderId->toString());
    }

    /**
     * @test
     */
    public function it_is_same_value_as(): void
    {
        $uuid = Uuid::uuid4();
        $orderId = new OrderId($uuid);
        $sameOrderId = new OrderId($uuid);

        $this->assertTrue($orderId->sameValueAs($sameOrderId));
    }

    /**
     * @test
     */
    public function it_is_not_same_value_as(): void
    {
        $orderId = new OrderId(Uuid::uuid4());
        $otherOrderId = new OrderId(Uuid::uuid4());

        $this->assertFalse($orderId->sameValueAs($otherOrderId));
    }
}
