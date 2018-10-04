<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    // 图片前缀
    public function preFixUrl($value,$data)
    {
        if($data['from']==1){
            $value = config('setting.img_url').$value;
        }
        return $value;
    }
}
