<?php

namespace app\lib\exception;

use Exception;
use think\exception\Handle;
use think\Log;

class ExceptionHandler extends Handle
{

    public $msg;
    public $code;
    public $errorCode;

    public function render(Exception $e)
    {
        if($e instanceof BaseException){
            $this->msg = $e->msg;
            $this->code = $e->code;
            $this->errorCode = $e->errorCode;
        }else{
            if(config('app_debug')){
                return parent::render($e);
            }else{
                $this->msg = '服务器内部错误';
                $this->code = 500;
                $this->errorCode = 999;
                // 记录日志
                $this->recordErrorLog($e);
            }
        }
        $result = [
            'ulr' => request()->url(),
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
        ];
        return json($result,$this->code);
    }

    private function recordErrorLog(Exception $e)
    {
        Log::init([
            'type' => 'File',
            'level' => ['error'],
        ]);
        Log::record($e->getMessage(),'error');
    }
    

}