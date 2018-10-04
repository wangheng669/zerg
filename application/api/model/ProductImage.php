<?php

namespace app\api\model;

class ProductImage extends BaseModel
{

    protected $hidden = ['delete_time','id','img_id','order','product_id'];

    public function img()
    {
        return $this->belongsTo('image','img_id','id');
    }
}
