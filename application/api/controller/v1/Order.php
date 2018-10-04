<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Order as OrderService;
use app\api\validate\OrderPlace;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;

class Order extends BaseController
{

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
    ];

    // 下单
    public function placeOrder($products)
    {
        (new OrderPlace())->goCheck();
        $orderService = new OrderService();
        $order = $orderService->place($products);
        return $order;
    }

    // 获取订单详情
    public function getOrderDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $order = OrderModel::getOrderByID($id);
        if(!$order){
            throw new OrderException();
        }
        return $order;
    }
}
