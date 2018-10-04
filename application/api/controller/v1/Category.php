<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\lib\exception\CategoryException;
use app\api\model\Category as CategoryModel;

class Category extends BaseController
{
    public function getCategory()
    {
        $category = CategoryModel::getCategoryAll();
        if($category->isEmpty()){
            throw new CategoryException();
        }
        return $category;
    }
}
