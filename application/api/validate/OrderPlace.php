<?php

namespace app\api\validate;

class OrderPlace extends BaseValidate{

    protected $rule = [
        'products' => 'checkProducts',
    ];

    protected $singleRule = [
        'product_id' => 'isPositiveInteger',
        'count' => 'isPositiveInteger',
    ];

    // 检测products数组
    public function checkProducts($value)
    {
        $products = $value;
        if(!is_array($products)){
            return false;
        }
        foreach($products as $product){
            if(!$this->checkProduct($product)){
                return false;
            }
        }
        return true;
    }

    // 检测单个product
    public function checkProduct($value)
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            return false;
        }
        return true;
    }

}