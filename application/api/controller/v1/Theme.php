<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\lib\exception\ThemeException;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Theme as ThemeModel;

class Theme extends BaseController
{
    // 获取专题列表
    public function getThemeList($ids='')
    {
        (new IDCollection())->goCheck();
        $theme = ThemeModel::getThemeByIDs($ids);
        if($theme->isEmpty()){
            throw new ThemeException();
        }
        return $theme;
    }
    // 获取专题详情
    public function getThemeDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $theme = ThemeModel::getThemeByID($id);
        if(!$theme){
            throw new ThemeException();
        }
        return $theme;
    }
    
}
