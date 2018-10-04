<?php

namespace app\lib\exception;

class ForbiddenException extends BaseException{
    
    public $msg = '权限不足';

    public $code = 404;

    public $errorCode = 80000;
}