<?php

namespace app\api\service;

use app\api\service\Token as TokenServer;
use app\api\model\Product as ProductModel;
use app\lib\exception\ProductException;
use app\lib\exception\AddressException;
use app\api\model\UserAddress;
use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct;
use think\Exception;
use think\Db;

class Order{

    private $oProducts;
    private $products;
    private $uid;

    // 下单主方法
    public function place($products)
    {
        // 用户提交商品信息
        $this->oProducts = $products;
        // 用户id
        $this->uid = TokenServer::getCurrentUID();
        // 数据库拿到的商品信息
        $this->products = $this->getProductsByOrder();

        $orderStatus = $this->getOrderStatus();
        if(!$orderStatus['pass']){
            $orderStatus['order_id'] = -1;
            return $orderStatus;
        }
        // 创建订单快照
        $snapOrder = $this->snapOrder($orderStatus);
        // 创建订单
        $order = $this->createOrder($snapOrder,$orderStatus);
        return $order;
    }

    // 创建订单快照
    private function snapOrder($orderStatus)
    {
        $snapOrder = [
            'snapName' => '',
            'snapImg' => '',
            'snapAdress' => '',
            'snapItems' => [],
        ];

        $snapOrder['snapName'] = $this->products[0]['name'];
        $snapOrder['snapImg'] = $this->products[0]['main_img_url'];
        $snapOrder['snapAddress'] = json_encode($this->getUserAddress());
        $snapOrder['snapItems'] = json_encode($orderStatus['productStatus']);
        if(count($this->products)>1){
            $snapOrder['snapName'] .= '等';
        }
        return $snapOrder;
    }

    // 创建订单
    private function createOrder($snapOrder,$orderStatus)
    {
        try{
            Db::startTrans();
            $order = new OrderModel();
            $order->order_no = self::makeOrderNo();
            $order->user_id = $this->uid;
            $order->total_price = $orderStatus['totalPrice'];
            $order->snap_img = $snapOrder['snapImg'];
            $order->snap_name = $snapOrder['snapName'];
            $order->snap_items = $snapOrder['snapItems'];
            $order->snap_address = $snapOrder['snapAddress'];
            $order->total_count = $orderStatus['totalCount'];
            $order->save();
            foreach($this->oProducts as &$product){
                $product['order_id'] = $order->id;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_id' => $order->id,
                'order_no' => $order->order_no,
                'create_time' => $order->create_time,
                'pass' => true,
            ];
        }catch(Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    // 创建订单编号
    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    // 获取用户地址
    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id',$this->uid)->find();
        if(!$userAddress){
            throw new AddressException([
                'msg' => '未查询到用户地址',
            ]);
        }
        return $userAddress;
    }

    // 商品订单校验
    private function getOrderStatus()
    {
        $orderStatus = [
            'totalPrice' => 0,
            'totalCount' => 0,
            'pass' => true,
            'productStatus' => [],
        ];
        foreach($this->oProducts as $oProduct){
            $productStatus = $this->getProductStatus($oProduct);
            if(!$productStatus['haveStock']){
                $orderStatus['pass'] = false;
            }
            $orderStatus['totalPrice'] += $productStatus['totalPrice'];
            $orderStatus['totalCount'] += $productStatus['count'];
            array_push($orderStatus['productStatus'],$productStatus);
        }
        return $orderStatus;
    }

    // 获取商品订单
    private function getProductStatus($oProduct)
    {
        $productStatus = [
            'totalPrice' => 0,
            'count' => 0,
            'haveStock' => false,
            'name' => '',
        ];
        $products = $this->products;
        $pIndex = -1;
        for($i=0;$i<count($products);$i++){
            if($oProduct['product_id']==$products[$i]['id']){
               $pIndex = $i; 
            }
        }
        if($pIndex==-1){
            throw new ProductException([
                'msg' => '商品不存在,订单创建失败',
            ]);
        }else{
            $product = $products[$pIndex];
            $productStatus['name'] = $product['name'];
            if($product['stock']>=$oProduct['count']){
                $productStatus['haveStock'] = true;
            }
            $productStatus['count'] = $oProduct['count'];
            $productStatus['totalPrice'] = $oProduct['count']*$product['price'];
        }
        return $productStatus;
    }
    
    // 获取商品详情
    private function getProductsByOrder()
    {
        $oPIDs = [];
        foreach($this->oProducts as $v){
            array_push($oPIDs,$v['product_id']);
        }
        $products = ProductModel::all($oPIDs);
        return $products;
    }

    // 校验订单库存量
    public function checkOrderStatus($orderID)
    {
        $this->oProducts = OrderProduct::where('order_id',$orderID)->find();
        $this->products = $this->getProductsByOrder();
        $order = $this->getOrderStatus();
        return $order;
    }

}