<?php

namespace app\api\validate;

class IDMustBePositiveInt extends BaseValidate{

    public $rule = [
        'id' => 'isPositiveInteger',
    ];
}