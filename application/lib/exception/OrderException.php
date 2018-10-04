<?php

namespace app\lib\exception;

class OrderException extends BaseException{
    
    public $msg = '订单不存在';

    public $code = 404;

    public $errorCode = 90002;
}