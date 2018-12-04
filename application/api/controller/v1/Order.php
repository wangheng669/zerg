<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use app\api\validate\OrderPlace;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParamter;
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

    // 获取用户历史订单
    public function getSummaryUser($page=1, $size=15)
    {
        (new PagingParamter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid,$size,$page);
        if($pagingOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage(),
            ];
        }
        $data = $pagingOrders->toArray();
        return [
            'data' => $pagingOrders,
            'current_page' => $pagingOrders->getCurrentPage(),
        ];
    }
}
