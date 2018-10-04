<?php

namespace app\api\validate;

use think\Validate;
use app\lib\exception\ParamterException;

class BaseValidate extends Validate{

    public function goCheck()
    {
        // 获取数据
        $data = request()->param();
        // 校验规则
        $result = $this->batch(true)->check($data);
        if(!$result){
            throw new ParamterException([
                'msg' => $this->getError(),
            ]);
        }
        return true;
    }

    // 校验正整数
    public function isPositiveInteger($value)
    {
        if(is_numeric($value)&&is_int($value+0)&&($value+0)>0){
            return true;
        }
        return false;
    }

    // 不能为空
    public function isNotEmpty($value)
    {
        if(empty($value)){
            return false;
        }
        return true;
    }

    // 判断手机号
    public function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    // 校验敏感参数
    public function checkDataRule($dataArray)
    {
        $newArray = [];
        if(array_key_exists('uid',$dataArray)||array_key_exists('uuser_id',$dataArray)){
            throw new ParamterException([
                'msg' => '参数非法',
            ]);
        }
        foreach($this->rule as $k=>$v){
            $newArray[$k] = $dataArray[$k];
        }
        return $newArray;
    }

}