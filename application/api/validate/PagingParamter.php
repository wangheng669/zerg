<?php

namespace app\api\validate;

class PagingParamter extends BaseValidate{

    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger',
    ];

    protected $message = [
        'page'=>'必须为正整数',
        'size'=>'必须为正整数'
    ];


}