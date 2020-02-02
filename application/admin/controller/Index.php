<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Controller{
	public function index(){
		// dump('123');
		return $this->fetch('login');
	}
	public function login(){
		
		
	}
}

?>