<?php

namespace app\lib\exception;

use think\Exception;

class BaseException extends Exception{
    
    public $msg = '参数错误';

    public $code = 404;

    public $errorCode = 10000;

    public function __construct($params=[])
    {
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }

}