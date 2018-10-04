<?php

namespace app\api\model;

class Banner extends BaseModel
{

    protected $hidden = ['delete_time','id','update_time'];

    protected $autoWriteTimestamp = true;

    // 关联banneritem表
    public function items()
    {
        return $this->hasMany('banner_item','banner_id','id');
    }

    public static function getBannerByID($id)
    {
        $banner = self::with(['items','items.img'])->find($id);
        return $banner;
    }
}
