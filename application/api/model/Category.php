<?php

namespace app\api\model;

class Category extends BaseModel
{

    protected $hidden = ['delete_time','id','topic_img_id','update_time'];

    // 关联image表
    public function topIcImg()
    {
        return $this->belongsTo('image','topic_img_id','id');
    }

    public static function getCategoryAll()
    {
        $category = self::all([],'topIcImg');
        return $category;
    }

}
