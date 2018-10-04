<?php

namespace app\lib\exception;

class BannerException extends BaseException{

    public $msg = 'banner未找到';

    public $code = 404;

    public $errorCode = 40000;

}