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
			$data = input('post.');
			$validate = new AddValidate();
			$user = UserModel::where('name',session('name'))->find();
			if(!$validate->check($data)){
				// die($validate->getError());
				return $this->error($validate->getError(),'user/admin_add');
			}
			// dump($data['name']);
			$user = UserModel::create([
				'name' => $data['name'],
				'pwd' => md5($data['pwd'])
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
			$data = input('post.');
			// dump($data['speciality'].$data['sname'].$data['ssex']);
			// die();
			$validate = new StudentValidate();
			// $user = UserModel::where('name',session('name'))->find();
			if(!$validate->check($data)){
				// die($validate->getError());
				return $this->error($validate->getError(),'user/student_add');
			}	
			//入学年份
			$sno_year = date("Y",time());
			//添加次序
			$sno_no = StudentModel::count()+1;
			//根据专业判断专业序号
			if($data['speciality'] == '计算机科学与技术'){
				$spno='107';
			}elseif($data['speciality'] == '软件工程'){
				$spno = '108';
			}elseif($data['speciality'] == '物联网'){
				$spno = '109';
			}elseif($data['speciality'] == '大数据'){
				$spno = '106';
			}
			if($sno_no<=9){
				$sno_no = '0'.$sno_no;
			}
			$sno = $sno_year.$spno.$data['sclass'].$sno_no;
			//$year_last_two = date("Y",time());			
			$sclass = $spno.substr(date('Y',time()),-2).$data['sclass'];
			
			$re = StudentModel::create([
				'sno' => $sno,
				'sname' => $data['sname'],
				'sbirthday' => $data['sbirthday'],
				'ssex' => $data['ssex'],
				'sclass' => $sclass,
				'speciality' => $data['speciality']				
				// 'pwd' => md5($data['pwd'])
			]);
			if($re){
				return $this->success('创建成功','student_list');		
			}else{
				return $this->error('创建失败，请重试');
			}
		}
		return $this->fetch();
	}

	//学生列表
	public function student_list(){

		$data = StudentModel::order('id')->limit(0)->paginate(3);
		$page = $data->render();
		// $this -> assign([
		// 	'id' => $data['id'],
		// 	'sno' => $data['sno'],
		// 	'sname' => $data['sname'],
		// 	'ssex' => $data['ssex'],
		// 	'sbirthday' => $data['sbirthday'],
		// 	'sclass' => $data['sclass'],
		// 	'speciality' => $data['speciality'],
		// 	'update_time' => $data['update'],
		// ]);
		$this -> assign('data',$data);
		$this -> assign('page',$page);
		return $this -> fetch();
	}

	//添加学生分数
	public function score_add(){
		if(!empty($_POST)){
			$data = input('post.');
			$validate = new AddValidate();
			$user = UserModel::where('name',session('name'))->find();
			if(!$validate->check($data)){
				// die($validate->getError());
				return $this->error($validate->getError(),'user/admin_add');
			}
			// dump($data['name']);
			$user = UserModel::create([
				'name' => $data['name'],
				'pwd' => md5($data['pwd'])
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

	//删除学生信息
	public function student_delete(){
		$id = input('get.id');
		$user = StudentModel::get($id);
		$user -> delete();
		$this -> success('删除成功','student_list');
	}

	//修改学生信息
	public function student_update(){
		if(!empty($_POST)){
			$data = input('post.');
			$id = input('get.id');
			$old_data = StudentModel::get($id);

			$validate = new StudentValidate();
			// $user = UserModel::where('name',session('name'))->find();
			if(!$validate->check($data)){
				// die($validate->getError());
				return $this->error($validate->getError(),'user/student_add');
			}	
			//入学年份
			$sno_year = date("Y",time());
			//添加次序
			$sno_no = StudentModel::count()+1;
			//根据专业判断专业序号
			if($data['speciality'] == '计算机科学与技术'){
				$spno='107';
			}elseif($data['speciality'] == '软件工程'){
				$spno = '108';
			}elseif($data['speciality'] == '物联网'){
				$spno = '109';
			}elseif($data['speciality'] == '大数据'){
				$spno = '106';
			}
			if($sno_no<=9){
				$sno_no = '0'.$sno_no;
			}
			substr($old_data['sclass'],3,2);
			// $sno = $sno_year.$spno.$data['sclass'].$sno_no;
			//$year_last_two = date("Y",time());			
			$sclass = $spno.substr($old_data['sclass'],3,2).$data['sclass'];
			
			$re = new StudentModel;
			$re -> save([
				// 'sno' => $sno,
				'sname' => $data['sname'],
				'sbirthday' => $data['sbirthday'],
				'ssex' => $data['ssex'],
				'sclass' => $sclass,
				'speciality' => $data['speciality']	
			],['id' => $id]);

			if($re){
				return $this->success('更新成功','student_list');		
			}else{
				return $this->error('更新失败，请重试');
			}

		}
		$id = input('get.id');
		$data = StudentModel::get($id);
		// dump($data);
		// dump(substr($data['sclass'],5,1));
		// dump(substr($data['create_time'],0,2));
		// die();
		$this -> assign([
			'id' => $data['id'],
			'sname' => $data['sname'],
			'sbirthday' => $data['sbirthday'],
			'ssex' => $data['ssex'],
			'sclass' => substr($data['sclass'],5,1),
			'speciality' => $data['speciality']	

		]);
		return $this -> fetch();
	}

	//修改学生信息数据库操作
	public function student_update_model(){
	

	
	}
}

?>