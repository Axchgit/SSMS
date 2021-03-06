<?php
namespace app\admin\validate;
use think\Validate;
class User extends Validate{
    protected $rule = [
        'name' => 'require',
        // 'pwd' => 'require|min:6',
        'code' => 'require|captcha',
    ];

    protected $message = [
        'name.require' => '名称不能为空',
        'pwd.require' => '密码不能为空',
        // 'pwd.min' => '密码不能小于6位',
        'code.require' => '验证码不能为空',
        'code.captcha' => '验证码错误',

    ];
}