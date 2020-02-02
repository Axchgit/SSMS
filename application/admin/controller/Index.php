<?php
namespace app\admin\controller;

use think\Controller;
use think\Model;
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
			$user = UserModel::where('name',$date['name'])->where('pwd',$date['pwd'])->find();
			if($user){
				session('name',$user['name']);
				return $this->success('恭喜你，登陆成功！','user/index');
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