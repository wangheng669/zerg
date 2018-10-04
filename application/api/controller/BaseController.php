<?php

namespace app\api\controller;

use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{

    // 只允许普通用户
    public function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }

    // 只允许普通用户
    public function checkPrimayScope()
    {
        TokenService::needPrimayScope();
    }

}
