<?php

namespace app\api\model;

class Order extends BaseModel
{
    protected $autoWriteTimestamp = true;

    protected $hidden = ['id','delete_time','create_time','user_id','prepay_id','update_time'];

    public function getSnapItemsAttr($value)
    {
        return json_decode($value,true);
    }
    
    public function getSnapAddressAttr($value)
    {
        return json_decode($value,true);
    }


    public static function getOrderByID($id)
    {
        $order = self::find($id);
        return $order;
    }

}
