<?php

namespace Stripe;

class PlanTest extends TestCase
{
    const TEST_RESOURCE_ID = 'plan';

    public function testIsListable()
    {
        $this->expectsRequest(
            'get',
            '/v1/plans'
        );
        $resources = Plan::all();
        $this->assertTrue(is_array($resources->data));
        $this->assertInstanceOf("Stripe\\Plan", $resources->data[0]);
    }

    public function testIsRetrievable()
    {
        $this->expectsRequest(
            'get',
            '/v1/plans/' . self::TEST_RESOURCE_ID
        );
        $resource = Plan::retrieve(self::TEST_RESOURCE_ID);
        $this->assertInstanceOf("Stripe\\Plan", $resource);
    }

    public function testIsCreatable()
    {
        $this->expectsRequest(
            'post',
            '/v1/plans'
        );
        $resource = Plan::create([
            'amount' => 100,
            'interval' => 'month',
            'currency' => 'usd',
            'name' => self::TEST_RESOURCE_ID,
            'id' => self::TEST_RESOURCE_ID
        ]);
        $this->assertInstanceOf("Stripe\\Plan", $resource);
    }

    public function testIsCreatableMetered()
    {
        $this->expectsRequest(
            'post',
            '/v1/plans'
        );
        $resource = Plan::create([
            'amount' => 100,
            'interval' => 'month',
            'currency' => 'usd',
            'name' => self::TEST_RESOURCE_ID,
            'id' => self::TEST_RESOURCE_ID,
            'usage_type' => 'metered'
        ]);
        $this->assertInstanceOf("Stripe\\Plan", $resource);
    }

    public function testIsCreatableTiered()
    {
        $this->expectsRequest(
            'post',
            '/v1/plans'
        );
        $resource = Plan::create([
            'interval' => 'month',
            'currency' => 'usd',
            'name' => self::TEST_RESOURCE_ID,
            'id' => self::TEST_RESOURCE_ID,
            'billing_scheme' => 'tiered',
            'tiers' => [['up_to' => 100, 'amount' => 100], ['up_to' => 'inf', 'amount' => 200]],
            'tiers_mode' => 'volume'
        ]);
        $this->assertInstanceOf("Stripe\\Plan", $resource);
    }

    public function testIsCreatableTransformUsage()
    {
        $this->expectsRequest(
            'post',
            '/v1/plans'
        );
        $resource = Plan::create([
            'amount' => 100,
            'interval' => 'month',
            'currency' => 'usd',
            'name' => self::TEST_RESOURCE_ID,
            'id' => self::TEST_RESOURCE_ID,
            'transform_usage' => ['round' => 'up', 'divide_by' => 10]
        ]);
        $this->assertInstanceOf("Stripe\\Plan", $resource);
    }

    public function testIsSaveable()
    {
        $resource = Plan::retrieve(self::TEST_RESOURCE_ID);
        $resource->metadata["key"] = "value";
        $this->expectsRequest(
            'post',
            '/v1/plans/' . $resource->id
        );
        $resource->save();
        $this->assertInstanceOf("Stripe\\Plan", $resource);
    }

    public function testIsUpdatable()
    {
        $this->expectsRequest(
            'post',
            '/v1/plans/' . self::TEST_RESOURCE_ID
        );
        $resource = Plan::update(self::TEST_RESOURCE_ID, [
            "metadata" => ["key" => "value"],
        ]);
        $this->assertInstanceOf("Stripe\\Plan", $resource);
    }

    public function testIsDeletable()
    {
        $resource = Plan::retrieve(self::TEST_RESOURCE_ID);
        $this->expectsRequest(
            'delete',
            '/v1/plans/' . $resource->id
        );
        $resource->delete();
        $this->assertInstanceOf("Stripe\\Plan", $resource);
    }
}
