<?php

namespace app\lib\exception;

class ProductException extends BaseException{
    
    public $msg = '产品不存在';

    public $code = 404;

    public $errorCode = 50000;
}