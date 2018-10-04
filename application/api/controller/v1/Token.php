<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\TokenGet;
use app\api\service\UserToken;

class Token extends BaseController
{
    // 获取token
    public function getToken($code='')
    {
        // 判断code是否为空
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token = $ut->get();
        return [
            'token' => $token,
        ];
    }
}
