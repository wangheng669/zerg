<?php

namespace app\api\model;

class Image extends BaseModel
{

    // 修改图片前缀
    public function getUrlAttr($data,$value)
    {
        return $this->preFixUrl($data,$value);
    }

    protected $hidden = ['update_time','delete_time','id','from'];
}
