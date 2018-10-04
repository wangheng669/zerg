<?php

namespace app\api\validate;

class IDCollection extends BaseValidate{

    public $rule = [
        'ids' => 'checkIDs',
    ];

    public function checkIDs($value)
    {
        // 判断是否为数组
        $idArray = explode(',',$value);
        if(is_array($idArray)&&$idArray){
            foreach($idArray as $value){
                if(!$this->isPositiveInteger($value)){
                    return false;
                }
            }
            return true;
        }
        return false;
    }

}