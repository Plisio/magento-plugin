<?php
namespace Plisio\PlisioGateway\Lib\Plisio;

use Plisio\PlisioGateway\Lib\PlisioGateway;
use Plisio\PlisioGateway\Lib\Plisio;
use Plisio\PlisioGateway\Lib\APIError\OrderIsNotValid;
use Plisio\PlisioGateway\Lib\APIError\OrderNotFound;

class Order extends Plisio
{
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function toHash()
    {
        return $this->order;
    }

    public function __get($name)
    {
        return $this->order[$name];
    }

    public static function find($orderId, $options = array(), $authentication = array())
    {
        try {
            return self::findOrFail($orderId, $options, $authentication);
        } catch (OrderNotFound $e) {
            return false;
        }
    }

    public static function findOrFail($orderId, $options = array(), $authentication = array())
    {
        $order = PlisioGateway::request('/orders/' . $orderId, 'GET', array(), $authentication);

        return new self($order);
    }

    public static function create($params, $options = array(), $authentication = array())
    {
        try {
            return self::createOrFail($params, $options, $authentication);
        } catch (OrderIsNotValid $e) {
            return false;
        }
    }

    public static function createOrFail($params, $options = array(), $authentication = array())
    {
        $order = PlisioGateway::request('/orders', 'GET', $params, $authentication);

        return new self($order);
    }
}
