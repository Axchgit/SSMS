<?php
namespace app\home\controller;
use app\home\model\User as UserModel;
use think\Controller;
use think\Db;
use app\home\view;
class Index extends Controller {
	public function index() {
//		echo request()->baseFile(); 
//		$arr = input(''); 
//		echo $arr['sex'];
//		Db::execute("update user set name='thinkphp' where name='tom'");
//		$result = Db::execute("insert into user (name,pwd) values('123',123)");
//		$result1 = Db::execute("select * from user");
//		dump($result1);
//		echo $result;
//		$result2 = Db::name('user')->where('id',1)->find();
//		dump($result2);
//		$list = UserModel::all();
//		$this->assign([
////			'name' =>'thinkPHP',
//			'email' => 'thinkphp@qq.com'
//		]);
// 		查询状态为1的用户数据 并且每页显示10条数据 
		$list = UserModel::paginate(2);
		$this->assign('list',$list);
		return $this->fetch('test');
		
	}
	public function add(){
		return $this->fetch('test1');
	} 
	public function addView(){
		    $data = Request::instance();
            print_r($data);		
	}
		
}

