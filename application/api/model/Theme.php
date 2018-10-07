<?php

namespace app\api\model;

class Theme extends BaseModel
{

    protected $hidden = ['delete_time','update_time','head_img_id','topic_img_id'];

    // 关联image表
    public function headImg()
    {
        return $this->belongsTo('image','head_img_id','id');
    }

    // 关联product表
    public function products()
    {
        return $this->belongsToMany('product','theme_product','theme_id','product_id');
    }
    
    // 关联image表
    public function topIcImg()
    {
        return $this->belongsTo('image','topic_img_id','id');
    }

    // 获取专题列表
    public static function getThemeByIDs($ids)
    {
        $idArray = explode(',',$ids);
        $theme = self::with(['topIcImg'])->select($idArray);
        return $theme;
    }

    // 获取专题详情
    public static function getThemeByID($id)
    {
        $theme = self::with(['products','topIcImg'])->find($id);
        return $theme;
    }

}
