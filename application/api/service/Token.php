<?php

namespace app\api\service;

use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use app\lib\enum\ScopeEnum;

class Token{

    // 生成Token
    public function generateToken(){
        $randChars  = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);
    }

    // 获取token的值
    public static function getCurrentTokenVal($key)
    {
        $token = request()->header('token');
        $result = cache($token);
        if(!$token||!$result){
            throw new TokenException([
                'msg' => 'token不存在'
            ]);
        }
        $cacheArray = json_decode($result,true);
        if(array_key_exists($key,$cacheArray)){
            return $cacheArray[$key];
        }else{
            throw new TokenException([
                'msg' => 'token值不存在'
            ]);
        }
    }

    // 检查订单的所属用户
    public static function checkUserOrder($uid)
    {
        $userID = self::getCurrentUID();
        if($userID!=$uid){
            return false;
        }
        return true;
    }

    // 获取token中的uid
    public static function getCurrentUID()
    {
        $uid = self::getCurrentTokenVal('uid');
        return $uid;
    }

    public static function needPrimayScope()
    {
        $scope = self::getCurrentTokenVal('scope');
        if($scope < ScopeEnum::User){
            throw new ForbiddenException([
                'msg' => '权限不足'
            ]);
        }
        return true;
    }
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVal('scope');
        if($scope != ScopeEnum::User){
            throw new ForbiddenException([
                'msg' => '权限不足'
            ]);
        }
        return true;
    }

}