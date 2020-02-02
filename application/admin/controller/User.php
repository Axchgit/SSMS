<?php
namespace app\admin\controller;
use think\Controller;
class User extends Base{
	public function list(){
		dump('123');
	}

	public function index(){
		return	$this -> fetch();
	}
	
	public function index_top(){
		return $this -> fetch();
	}

	public function index_left(){
		return $this -> fetch();
	}

	public function index_swich(){
		return $this -> fetch();
	}

	public function index_main(){
		return $this -> fetch();
	}

	public function index_bottom(){
		return $this -> fetch();
	}
}

?>