<?php
namespace app\admin\validate;
use think\Validate;
class Add extends Validate{
    protected $rule = [
        'name|名称' => 'require|min:3|max:20',
        'pwd|密码' => 'require|min:6',
    ];

    protected $message = [
        'name.require' => '名称不能为空',
        'name.min' => '名称太短',
        'name.max' => '名称太长',
        'pwd.require' => '密码不能为空',
        'pwd.min' => '密码不能小于6位',

    ];
}