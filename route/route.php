<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//路由定义测试
//Route::get('/', function () { return 'Hello,world!';
//});
//路由定义demo
//Route::rule('new/:id','home/index/index');

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('admin.think.com', 'index/hello');

return [

];
