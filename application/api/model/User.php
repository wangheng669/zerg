<?php

namespace app\api\model;

class User extends BaseModel
{
    protected $autoWriteTimestamp = true;

    // 关联address表
    public function address()
    {
        return $this->hasOne('user_address','user_id','id');
    }

    // 根据openid获取user
    public static function getUserByOpenID($openid)
    {
        $user = self::where('openid',$openid)->find();
        return $user;
    }

    // 根据uid获取user
    public static function getUserByUID($uid)
    {
        $user = self::with(['address'])->find($uid);
        return $user;
    }
}
