<?php
namespace app\admin\controller;

use think\Controller;
use think\Model;
use think\facade\Request;
use app\admin\model\User as UserModel;
use app\admin\validate\User as UserValidate;


class Index extends Controller{
	public function index(){
		// dump('123');
		return $this->fetch();
	}
	public function login(){
		// $data = input('');
		return $this->fetch('login');

		
	}

	public function checkLogin(){
		$validate = new UserValidate();
		$date = input('post.');
		if($validate->check($date)){
			$user = UserModel::where('name',$date['name'])->where('pwd',md5($date['pwd']))->find();
			if($user){
				$ip = Request::ip();
				//登录次数加1
				$user->login_num+=1;
				//保存上次登录时间
				$user->old_update_time = $user->update_time;
				//保存上次登录IP
				$user->old_ip_address = $user->new_ip_address;
				//添加本次登录IP
				$user->new_ip_address = $ip;
				$user->save();
				session('name',$user['name']);
				return $this->success('恭喜你，登陆成功！','user/index');
			}else{
				return $this->error('用户名或密码错误');
			}
		}else{
			// dump($validate->getError());
			$this -> error($validate->getError(),'index/login');
		}
	}

	public function test(){
		return $this->fetch('user/add');
	}
}

?>