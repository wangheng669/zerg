<?php

namespace app\api\model;

class Product extends BaseModel
{
    protected $hidden = ['create_time','delete_time','img_id','pivot','from','summary','update_time'];


    // 关联property
    public function property()
    {
        return $this->hasMany('product_property','product_id','id');
    }
    
    // 关联image
    public function imgs()
    {
        return $this->hasMany('product_image','product_id','id');
    }

    // 修改图片前缀
    public function getMainImgUrlAttr($data,$value)
    {
        return $this->preFixUrl($data,$value);
    }

    // 获取最近商品
    public static function getProductByCount($count)
    {
        $product = self::order('create_time','desc')->limit($count)->select();
        return $product;
    }

    // 获取商品详情
    public static function getProductByID($id)
    {
        $product = self::with(['imgs'=>function($query){
            $query->with(['img'])->order('order','asc');
        }])->with(['property'])->find($id);
        return $product;
    }
    
    // 根据分类获取商品
    public static function getProductByCategoryID($id)
    {
        $product = self::where('category_id',$id)->select();
        return $product;
    }

}
