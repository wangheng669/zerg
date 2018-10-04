<?php

namespace app\lib\exception;

class ThemeException extends BaseException{
    
    public $msg = '参数校验错误';

    public $code = 404;

    public $errorCode = 30000;
}