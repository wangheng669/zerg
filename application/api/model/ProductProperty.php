<?php

namespace app\api\model;

class ProductProperty extends BaseModel
{
    protected $hidden = ['delete_time','update_time','id','product_id'];
}
