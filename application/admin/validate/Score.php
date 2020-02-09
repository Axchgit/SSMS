<?php
namespace app\admin\validate;
use think\Validate;
class Score extends Validate{
    protected $rule = [
        'chinese|语文' => 'require|number|between:1,100',
        'mathematics|数学' => 'require|number|between:1,100',
        'english|英语' => 'require|number|between:1,100',

    ];

    protected $message = [
        'chinese.require' => '1不能为空',
        'chinese.number' => '1必须为数字',
        'chinese.between' => '1必须为1-100之间',
        'mathematics.require' => '2不能为空',
        'mathematics.number' => '2必须为数字',
        'mathematics.between' => '2必须为1-100之间',
        'english.require' => '3不能为空',
        'english.number' => '3必须为数字',
        'english.between' => '3必须为1-100之间',
    ];
}