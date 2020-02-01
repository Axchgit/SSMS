<?php
namespace app\admin\controller;
use think\Controller;
class Base extends Controller{
	protected function initialize() {
		if(!session('name')){
			$this->error('请先登录','Index/login');
		} 
		
	}
	
}

?>