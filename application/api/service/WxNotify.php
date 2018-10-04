<?php

namespace app\api\service;

use think\Loader;
use think\Log;
use think\Db;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\model\Product  as ProductModel;
use app\lib\enum\OrderStatusEnum;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class WxNotify extends \WxPayNotify{


    // 回调返回订单编号
    // 根据订单编号进行库存量检测
    // 更新订单状态和库存
    // 回调处理[重写父类方法]
    public function NotifyProcess($objData, $config, &$msg)
    {
        // 判断是否支付成功
        if($objData->values['result_code'] == 'SUCCESS'){
            $orderNo = $objData->values['out_trade_no'];
            Db::startTrans();
            try{
                $order = OrderModel::where('order_no',$orderNo)->find();
                // 检测订单状态是否为未支付
                if($order['status'] == 1){
                    $orderService = new OrderService();
                    // 检测库存量
                    $stockStatus = $orderService->checkOrderStatus($order->id);
                    if($stockStatus['pass']){
                        // 更新订单状态
                        $this->updateOrderStatus($order->id,true);
                        // 更新库存
                        $this->reduceStock($stockStatus);
                    }else{
                        // 更新订单状态
                        $this->updateOrderStatus($order->id,false);
                    }
                }
                Db::commit();
            }catch(Exception $ex){
                Db::rollback();
                return false;
            }
        }else{
           return true;
        }
    }

    // 更新每个商品的库存
    private function reduceStock($stockStatus)
    {
        foreach($stockStatus['productStatus'] as $signlePStatus){
            ProductModel::where('id',$signlePStatus['id'])->setDec('stock',$signlePStatus['count']);
        }
    }

    // 更新订单状态
    private function updateOrderStatus($orderID,$success)
    {
        $status = $success?OrderStatusEnum::PAID:OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id',$orderID)->update(['status'=>$status]);
    }


}