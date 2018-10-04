<?php

namespace app\lib\exception;

class TokenException extends BaseException{
    
    public $msg = 'token无效';

    public $code = 404;

    public $errorCode = 70000;
}