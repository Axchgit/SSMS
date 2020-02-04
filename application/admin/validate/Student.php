<?php
namespace app\admin\validate;
use think\Validate;
class Student extends Validate{
    protected $rule = [
        'sname|姓名' => 'require|min:2|max:10',

        // 'pwd|密码' => 'require|min:6',
    ];

    protected $message = [
        'sname.require' => '姓名不能为空',
        'sname.min' => '姓名太短',
        'sname.max' => '姓名太长',
        // 'pwd.require' => '密码不能为空',
        // 'pwd.min' => '密码不能小于6位',

    ];
}