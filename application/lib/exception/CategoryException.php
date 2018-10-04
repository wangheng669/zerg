<?php

namespace app\lib\exception;

class CategoryException extends BaseException{

    public $msg = '分类不存在';

    public $code = 404;

    public $errorCode = 60000;

}