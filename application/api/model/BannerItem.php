<?php

namespace app\api\model;

class BannerItem extends BaseModel
{

    protected $hidden = ['update_time','delete_time','img_id','type','id','banner_id'];

    // 关联image表
    public function img()
    {
        return $this->belongsTo('image','img_id','id');
    }
}
