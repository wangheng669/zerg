<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\lib\exception\SuccessMessage;

class Address extends BaseController
{

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'createorupdate'],
    ];


    // 更新地址
    public function createOrUpdate()
    {
        $validate = new AddressNew();
        $validate->goCheck();
        $uid = TokenService::getCurrentUID();
        $dataArray = $validate->checkDataRule(request()->post());
        $user = UserModel::getUserByUID($uid);
        if(!$user->address){
            $user->address()->save($dataArray);
        }else{
            $user->address->save($dataArray);
        }
        return new SuccessMessage();
    }

    // 获取用户地址
    public function getAddress()
    {
        $uid = TokenService::getCurrentUid();
        $address = UserAddress::getAddressUID($uid);
        return $address;
    }

}
