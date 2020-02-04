<?php
namespace app\admin\controller;

use app\admin\validate\User as UserValidate;
use app\admin\validate\Add as AddValidate;
use app\admin\validate\Student as StudentValidate;


use think\Controller;
use think\Db;
use think\facade\Request;
use app\admin\model\User as UserModel;
use app\admin\model\Student as StudentModel;

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
	//系统管理界面
	public function index_main(){
		$user = UserModel::where('name',session('name'))->find();
		//管理员个数
		$count = UserModel::count();
		//登录次数
		$login_num = $user->login_num;
		//上次登录时间
		$login_time = $user->old_update_time;
		//上次登录IP地址
		$ip_address = $user->old_ip_address;
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
	//添加管理员
	public function admin_add(){
		if(!empty($_POST)){
			$date = input('post.');
			$validate = new AddValidate();
			$user = UserModel::where('name',session('name'))->find();
			if(!$validate->check($date)){
				// die($validate->getError());
				return $this->error($validate->getError(),'user/admin_add');
			}
			// dump($date['name']);
			$user = UserModel::create([
				'name' => $date['name'],
				'pwd' => md5($date['pwd'])
			]);
			if($user){
				return $this->success('创建成功');		
			}else{
				return $this->error('创建失败，请重试');
			}
		}
		$user = UserModel::where('name',session('name'))->find();
		$pwd = $user->pwd;
		$this->assign([
			'pwd' => $pwd,
		]);

		return $this->fetch();
	}
	//添加学生信息
	public function student_add(){
		if(!empty($_POST)){
			$date = input('post.');
			// dump($date['speciality'].$date['sname'].$date['ssex']);
			// die();
			$validate = new StudentValidate();
			// $user = UserModel::where('name',session('name'))->find();
			if(!$validate->check($date)){
				// die($validate->getError());
				return $this->error($validate->getError(),'user/student_add');
			}	
			//入学年份
			$sno_year = date("Y",time());
			//添加次序
			$sno_no = StudentModel::count()+1;
			//根据专业判断专业序号
			if($date['speciality'] == '计算机科学与技术'){
				$spno='107';
			}elseif($date['speciality'] == '软件工程'){
				$spno = '108';
			}elseif($date['speciality'] == '物联网'){
				$spno = '109';
			}elseif($date['speciality'] == '大数据'){
				$spno = '106';
			}
			if($sno_no<=9){
				$sno_no = '0'.$sno_no;
			}
			$sno = $sno_year.$spno.$date['sclass'].$sno_no;
			//$year_last_two = date("Y",time());			
			$sclass = $spno.substr(date('Y',time()),-2).$date['sclass'];
			
			$re = StudentModel::create([
				'sno' => $sno,
				'sname' => $date['sname'],
				'sbirthday' => $date['sbirthday'],
				'ssex' => $date['ssex'],
				'sbirthday' => $date['sbirthday'],
				'sclass' => $sclass,
				'speciality' => $date['speciality']				
				// 'pwd' => md5($date['pwd'])
			]);
			if($re){
				return $this->success('创建成功');		
			}else{
				return $this->error('创建失败，请重试');
			}
		}
		return $this->fetch();
	}

	//添加学生分数
	public function score_add(){
		if(!empty($_POST)){
			$date = input('post.');
			$validate = new AddValidate();
			$user = UserModel::where('name',session('name'))->find();
			if(!$validate->check($date)){
				// die($validate->getError());
				return $this->error($validate->getError(),'user/admin_add');
			}
			// dump($date['name']);
			$user = UserModel::create([
				'name' => $date['name'],
				'pwd' => md5($date['pwd'])
			]);
			if($user){
				return $this->success('创建成功');		
			}else{
				return $this->error('创建失败，请重试');
			}
		}
		// $user = UserModel::where('name',session('name'))->find();
		// $pwd = $user->pwd;
		// $this->assign([
		// 	'pwd' => $pwd,
		// ]);

		return $this->fetch();

	}




}

?>