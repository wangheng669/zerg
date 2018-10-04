<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;
use app\api\model\Product as ProductModel;

class Product extends BaseController
{
    // 获取最近商品
    public function getRecent($count=15)
    {
        (new Count())->goCheck();
        $product = ProductModel::getProductByCount($count);
        if($product->isEmpty()){
            throw new ProductException();
        }
        return $product;
    }

    // 获取商品详情
    public function getProductDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductByID($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }

    // 根据商品分类获取产品
    public function getProductInCategory($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductByCategoryID($id);
        if($product->isEmpty()){
            throw new ProductException();
        }
        return $product;
    }

}
