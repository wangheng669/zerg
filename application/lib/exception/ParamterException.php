<?php

namespace app\lib\exception;

class ParamterException extends BaseException{
    
    public $msg = '参数校验错误';

    public $code = 404;

    public $errorCode = 20000;
}