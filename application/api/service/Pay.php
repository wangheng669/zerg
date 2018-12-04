<?php

namespace app\api\service;

use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use app\lib\enum\OrderStatusEnum;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay{
 
    protected $orderID;
    protected $orderNo;

    public function __construct($orderID){
        $this->orderID = $orderID;
    }

    public function pay()
    {
        // 校验订单是否正确
        $this->checkOrderValid();
        // 检查库存量
        $orderService = new OrderService();
        $order = $orderService->checkOrderStatus($this->orderID);
        if(!$order['pass']){
            throw new OrderException([
                'msg' => '订单库存量不足',
            ]);
        }
        return $this->makeWxPreOrder($order['totalPrice']);
    }

    // 创建预订单
    private function makeWxPreOrder($totalPrice)
    {
        $openid = TokenService::getCurrentTokenVal('openid');
        if(!$openid){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        // 设置订单号
        $wxOrderData->SetOut_trade_no($this->orderNo);
        // 设置支付类型
        $wxOrderData->SetTrade_type('JSAPI');
        // 设置金额
        $wxOrderData->SetTotal_fee(1);
        // 设置项目描述
        $wxOrderData->SetBody('零食商贩');
        // 设置openid
        $wxOrderData->SetOpenid($openid);
        // 设置回调链接
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        // 获取签名
        return $this->getPaySignature($wxOrderData);
    }

    // 访问微信服务器获取签名
    private function getPaySignature($wxOrderData)
    {
        $wxConfig = new \WxPayConfig();
        $wxOrder = \WxPayApi::unifiedOrder($wxConfig,$wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS'
        || $wxOrder['result_code'] != 'SUCCESS')
        {
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
            return false;
        }
        $this->recordPreOrder($wxOrder);
        // 获取签名
        $signaTure = $this->sign($wxOrder,$wxConfig);
        return $signaTure;
    }

    // 处理预订单
    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id',$this->orderID)->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }

    // 生成sign签名
    private function sign($wxOrder,$config)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign($config);
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }

    // 校验订单是否正确
    private function checkOrderValid()
    {
        // 判断订单是否存在
        $order = OrderModel::find($this->orderID);
        if(!$order){
            throw new OrderException([
                'msg' => '订单不存在',
            ]);
        }
        // 判断订单是否属于当前用户
        if(!TokenService::checkUserOrder($order->user_id)){
            throw new OrderException([
                'msg' => '订单所属用户错误',
            ]);
        }
        // 判断订单状态是否为未支付
        if($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg' => '订单状态有误',
            ]);
        }
        $this->orderNo = $order->order_no;
    }

}