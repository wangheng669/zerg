<?php

namespace app\api\model;

class UserAddress extends BaseModel
{

    // 获取用户地址
    public static function getAddressUID($uid)
    {
        $address = self::where('user_id',$uid)->find();
        return $address;
    }

}
