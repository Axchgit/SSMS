<?php
namespace app\home\controller;
use app\home\model\User as UserModel;
class User {
	public function add(){
		/*可以把create的参数改为数组 例如
		 * $user['name'] = 'thinkphp';
		 * $user['pwd'] = '123';
		 * create($user)
		*/
		//此为静态调用，不需要实例化：UserModel::create
		$user = UserModel::create([ 'name' => 'thinkphp', 'pwd' => '5455']);
		echo $user->name;
		echo $user->pwd;
		echo $user->id; // 获取自增ID
		
	}
	public function addList(){
		$user = new UserModel;
		$list = [
			['name'=>'qwe','pwd'=>'123'],
			['name'=>'rty','pwd'=>'456']			
		];
		if($re = $user->saveAll($list)){
			echo "success";
		}else{
			echo "filed";
		}
	}
	
	public function update(){
		$user = UserModel::get(3);
		$user->name = 'yyyyyy';
		$user->pwd = '12356';
		$user->save();
	}
	public function select(){
//		$user = UserModel::get(1);
//		echo $user->name;
//		
//		$user = UserModel::where('name','123')->find();
//		echo $user->pwd;
//		//get()的用法；
//		$user = UserModel::get(['name'=>'123']);
//		echo $user->pwd;
//		//查询多个数据
//		$list = UserModel::all('1,2,3');//或数组all([1,2,3])
//		foreach($list as $key=>$value){
//			echo $value->name;
//		}	
		$list = UserModel::where('name',123)->limit(3)->order('id','asc')->select();
		foreach($list as $key=>$value){
			echo $value['name'];
		}		
	}
	public function delete(){
//			$user = UserModel::get(1);
//			$user->delete();
			//批量删除
//			UserModel::destroy(1);
//			UserModel::destroy('1,2,3');
			//条件删除
//			UserModel::destroy(
//				function($query){
//					$query->where('id','>',8);
//				}
//			);
			//通过数据库类的查询条件删除
			UserModel::where('id','>',7)->delete();
			
			
		}
	
}

?>