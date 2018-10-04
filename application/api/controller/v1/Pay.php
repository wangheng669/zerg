<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;


class Pay extends BaseController
{
    // 统一下单接口
    public function preOrder($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }

    // 回调接口
    public function receiveNotify()
    {
        // 调用微信自带的获取回调方法
        $wxNotify = new WxNotify();
        $wxConfig = new \WxPayConfig();
        $wxNotify->Handle($wxConfig);
    }

}
