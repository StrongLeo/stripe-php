<?php

namespace Stripe;

/**
 * Class UsageRecord
 *
 * @package Stripe
 *
 * @property string $id
 * @property string $object
 * @property bool $livemode
 * @property int $quantity
 * @property string $subscription_item
 * @property int $timestamp
 */
class UsageRecord extends ApiResource
{
    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return \Stripe\ApiResource The created resource.
     */
    public static function create($params = null, $options = null)
    {
        self::_validateParams($params);
        $subscription_item = $params['subscription_item'];
        $url = "/v1/subscription_items/$subscription_item/usage_records";
        $request_params = $params;
        unset($request_params['subscription_item']);

        list($response, $opts) = static::_staticRequest('post', $url, $request_params, $options);
        $obj = \Stripe\Util\Util::convertToStripeObject($response->json, $opts);
        $obj->setLastResponse($response);
        return $obj;
    }
}
