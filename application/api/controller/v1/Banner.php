<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\lib\exception\BannerException;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;

class Banner extends BaseController
{
    public function getBanner($id)
    {
        // 参数校验
        (new IDMustBePositiveInt())->goCheck();
        $banner = BannerModel::getBannerByID($id);
        if(!$banner){
            throw new BannerException();
        }
        return $banner;
    }
}
