<?php

namespace app\api\validate;

class Count extends BaseValidate
{

    protected $rule = [
        'count' => 'require|between:1,15',
    ];

}