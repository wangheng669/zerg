<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\TokenGet;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\lib\exception\paramterException;

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

    public static function verifyToken($token='')
    {
        if(!$token){
            throw new ParamterException([
                'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid,
        ];
    }
}
