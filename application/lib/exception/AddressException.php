<?php

namespace app\lib\exception;

class AddressException extends BaseException{
    
    public $msg = '地址不存在';

    public $code = 404;

    public $errorCode = 90000;
}