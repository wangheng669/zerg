<?php

namespace app\api\service;

use app\lib\exception\TokenException;
use app\api\model\User as UserModel;
use think\Exception;
use app\lib\enum\ScopeEnum;

class UserToken extends Token{

    private $appID;
    private $appSecret;
    private $loginUrl;

    // 初始化微信请求地址
    public function __construct($code)
    {
        $this->appID = config('wx.app_id');
        $this->appSecret = config('wx.app_secret');
        $this->loginUrl = sprintf(config('wx.login_url'),$this->appID,$this->appSecret,$code);
    }

    // 获取token主方法
    public function get()
    {
        $result = curl_get($this->loginUrl);
        $wxResult = json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('微信内部错误,获取openId和session_key异常');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);
            }
        }
    }

    // 处理正确token
    private function grantToken($wxResult)
    {
        $openid = $wxResult['openid'];
        // 判断该openid所属用户是否存在
        $user = UserModel::getUserByOpenID($openid);
        if(!$user){
            $user = $this->newUser($openid);
        }
        $uid = $user->id;
        // 准备token缓存
        $cacheResult = $this->prepareTokenCached($uid,$wxResult);
        // 保存并返回token码
        return $this->saveTokenCached($cacheResult);
    }

    // 准备token缓存
    private function prepareTokenCached($uid,$wxResult)
    {
        $cacheResult = $wxResult;
        $cacheResult['uid'] = $uid;
        $cacheResult['scope'] = ScopeEnum::User;
        return $cacheResult;
    }

    // 保存并返回token码
    private function saveTokenCached($cacheResult)
    {
        $key = $this->generateToken();
        $value = json_encode($cacheResult);
        $expire_in = config('setting.expire_in');
        $result = cache($key,$value,$expire_in);
        if(!$result){
            throw new Exception('token保存失败');
        }
        return $key;
    }

    // 创建用户
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user;
    }

    // 处理token错误
    private function processLoginError($wxResult)
    {
        throw new TokenException([
            'msg' => $wxResult['errMsg'],
            'errorCode' => $wxResult['errcode'],
        ]);
    }

}