<?php
namespace app\admin\controller;

use app\admin\validate\User as UserValidate;
use app\admin\validate\Add as AddValidate;

use think\Controller;
use think\Db;
use think\facade\Request;
use app\admin\model\User as UserModel;

class User extends Base{

	public function index(){
		return	$this -> fetch();
	}
	
	public function index_top(){
		return $this -> fetch();
	}

	public function index_left(){
		$user = UserModel::where('name',session('name'))->find();
		$name = $user->name;
		$this ->assign([

			'name' => $name

		]);


		return $this -> fetch();
	}

	public function index_swich(){
		return $this -> fetch();
	}

	public function index_main(){
		$user = UserModel::where('name',session('name'))->find();
		//管理员个数
		$count = UserModel::count();
		//登录次数
		$login_num = $user->login_num;
		//上次登录时间
		$login_time = $user->update_time;
		//上次登录IP地址
		$ip_address = $user->ip_address;
		//管理员名称
		$name = $user->name;
		$re = new UserModel();
		//五分时间(凌晨/早上/中午/下午/晚上)
		$mtime = $re->getStrTime();
		//当前IP地址
		$ip = Request::ip();
		//当前端口号
		$port = Request::port();
		//当前域名
		$host = Request::host();
		//数据库版本号
		$mysql = Db::query('select version()');
		// dump($mysql['0']['version()']);
		// die();
		$this ->assign([
			'ip' => $ip,
			'port' => $port,
			'host' => $host,
			'mysql' => $mysql,
			'login_num' => $login_num,
			'login_time' => $login_time,
			'ip_address' => $ip_address,
			'count' => $count,
			'name' => $name,
			'mtime' => $mtime
		]);
		return $this -> fetch();
	}

	public function index_bottom(){
		return $this -> fetch();
	}

	public function loginOut(){
		$re = session(null);		
		return $this -> success('注销成功','index/login');		
	}

	public function add(){
		if(!empty($_POST)){
			$date = input('post.');
			$validate = new AddValidate();
			$user = UserModel::where('name',session('name'))->find();
			if(!$validate->check($date)){
				// die($validate->getError());
				return $this->error($validate->getError(),'user/add');
			}
			// dump($date['name']);
			$user = UserModel::create([
				'name' => $date['name'],
				'pwd' => md5($date['pwd'])
			]);
			return $this->success('创建成功');			
			// die();
		}
		$user = UserModel::where('name',session('name'))->find();
		$pwd = $user->pwd;
		$this->assign([
			'pwd' => $pwd,
		]);

		return $this->fetch();
	}
}

?>