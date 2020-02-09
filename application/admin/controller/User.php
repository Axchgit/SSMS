<?php

namespace app\admin\controller;

use app\admin\validate\User as UserValidate;
use app\admin\validate\Add as AddValidate;
use app\admin\validate\Student as StudentValidate;
use app\admin\validate\Score as ScoreValidate;


use think\Controller;
use think\Db;
use think\Model;
use think\facade\Request;
use app\admin\model\User as UserModel;
use app\admin\model\Student as StudentModel;
use app\admin\model\Score as ScoreModel;
use app\api\controller\Score;
use app\api\controller\Student;

class User extends Base
{

	public function index()
	{
		return	$this->fetch();
	}

	public function index_top()
	{
		return $this->fetch();
	}

	public function index_left()
	{
		$user = UserModel::where('name', session('name'))->find();
		$name = $user->name;
		$this->assign([
			'name' => $name
		]);
		return $this->fetch();
	}

	public function index_swich()
	{
		return $this->fetch();
	}
	//系统管理界面
	public function index_main()
	{
		$user = UserModel::where('name', session('name'))->find();
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
		$this->assign([
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

		return $this->fetch();
	}

	public function index_bottom()
	{
		return $this->fetch();
	}

	public function loginOut()
	{
		$re = session(null);
		return $this->success('注销成功', 'index/login');
	}
	//添加管理员
	public function admin_add()
	{
		if (!empty($_POST)) {
			$data = input('post.');
			$validate = new AddValidate();
			$user = UserModel::where('name', session('name'))->find();
			if (!$validate->check($data)) {
				// die($validate->getError());
				return $this->error($validate->getError(), 'user/admin_add');
			}
			// dump($data['name']);
			$user = UserModel::create([
				'name' => $data['name'],
				'pwd' => md5($data['pwd'])
			]);
			if ($user) {
				return $this->success('创建成功');
			} else {
				return $this->error('创建失败，请重试');
			}
		}
		$user = UserModel::where('name', session('name'))->find();
		$pwd = $user->pwd;
		$this->assign([
			'pwd' => $pwd,
		]);

		return $this->fetch();
	}
	//添加学生信息
	public function student_add()
	{
		if (!empty($_POST)) {
			$data = input('post.');
			// dump($data['speciality'].$data['sname'].$data['ssex']);
			// die();
			$validate = new StudentValidate();
			// $user = UserModel::where('name',session('name'))->find();
			if (!$validate->check($data)) {
				// die($validate->getError());
				return $this->error($validate->getError(), 'student_add');
			}
			//入学年份
			$sno_year = date("Y", time());
			//添加次序
			$sno_no = StudentModel::count() + 1;
			//根据专业判断专业序号
			if ($data['speciality'] == '计算机科学与技术') {
				$spno = '107';
			} elseif ($data['speciality'] == '软件工程') {
				$spno = '108';
			} elseif ($data['speciality'] == '物联网') {
				$spno = '109';
			} elseif ($data['speciality'] == '大数据') {
				$spno = '106';
			}
			if ($sno_no <= 9) {
				$sno_no = '0' . $sno_no;
			}
			$sno = $sno_year . $spno . $data['sclass'] . $sno_no;
			//$year_last_two = date("Y",time());			
			$sclass = $spno . substr(date('Y', time()), -2) . $data['sclass'];

			$re = StudentModel::create([
				'sno' => $sno,
				'sname' => $data['sname'],
				'sbirthday' => $data['sbirthday'],
				'ssex' => $data['ssex'],
				'sclass' => $sclass,
				'speciality' => $data['speciality']
				// 'pwd' => md5($data['pwd'])
			]);
			if ($re) {
				return $this->success('创建成功', 'student_list');
			} else {
				return $this->error('创建失败，请重试');
			}
		}
		return $this->fetch();
	}

	//学生列表
	public function student_list()
	{
		$data = StudentModel::order('id')->limit(0)->paginate(10);
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

		$this->assign('data', $data);
		$this->assign('page', $page);
		//isEmpty()判断数据集是否为空
		if ($data->isEmpty()) {
			// foreach ($data as $key);
			// if (empty($key['sno'])) {
			return $this->fetch('index/empty');
		}
		// $this -> assign('sno',$key['sno']);
		// dump($key['sno']);
		// die();
		return $this->fetch();
	}

	//删除学生信息
	public function student_delete()
	{
		$id = input('get.id');
		$re = StudentModel::destroy($id);
		// $user->delete();
		if ($re) {
			return $this->success('删除成功', 'student_list');
		} else {
			return $this->error('删除失败，请重试', 'student_list');
		}
	}

	//修改学生信息
	public function student_update()
	{
		if (!empty($_POST)) {
			$data = input('post.');
			$id = input('get.id');
			$old_data = StudentModel::get($id);
			$validate = new StudentValidate();
			// $user = UserModel::where('name',session('name'))->find();
			if (!$validate->check($data)) {
				// die($validate->getError());
				return $this->error($validate->getError(), 'student_add');
			}
			//入学年份
			$sno_year = date("Y", time());
			//添加次序
			$sno_no = StudentModel::count() + 1;
			//根据专业判断专业序号
			if ($data['speciality'] == '计算机科学与技术') {
				$spno = '107';
			} elseif ($data['speciality'] == '软件工程') {
				$spno = '108';
			} elseif ($data['speciality'] == '物联网') {
				$spno = '109';
			} elseif ($data['speciality'] == '大数据') {
				$spno = '106';
			}
			if ($sno_no <= 9) {
				$sno_no = '0' . $sno_no;
			}
			substr($old_data['sclass'], 3, 2);
			// $sno = $sno_year.$spno.$data['sclass'].$sno_no;
			//$year_last_two = date("Y",time());			
			$sclass = $spno . substr($old_data['sclass'], 3, 2) . $data['sclass'];
			$re = new StudentModel;
			$re->save([
				// 'sno' => $sno,
				'sname' => $data['sname'],
				'sbirthday' => $data['sbirthday'],
				'ssex' => $data['ssex'],
				'sclass' => $sclass,
				'speciality' => $data['speciality']
			], ['id' => $id]);
			if ($re) {
				return $this->success('更新成功', 'student_list');
			} else {
				return $this->error('更新失败，请重试');
			}
		}
		$id = input('get.id');
		$data = StudentModel::get($id);
		$this->assign([
			'id' => $data['id'],
			'sname' => $data['sname'],
			'sbirthday' => $data['sbirthday'],
			'ssex' => $data['ssex'],
			'sclass' => substr($data['sclass'], 5, 1),
			'speciality' => $data['speciality']
		]);
		return $this->fetch();
	}

	//添加学生分数
	public function score_add()
	{
		if (!empty($_POST)) {
			$data = input('post.');
			$sno = input('get.sno');
			// dump($data['sno']);
			// die();
			$validate = new ScoreValidate();
			if (!$validate->check($data)) {
				// die($validate->getError());
				return $this->error($validate->getError(), 'score_add');
			}
			// dump($data['name']);
			$re = ScoreModel::create([
				'sno' => $sno,
				'chinese' => $data['chinese'],
				'mathematics' => $data['mathematics'],
				'english' => $data['english'],
				'semester' => $data['semester'],
			]);
			if ($re) {
				return $this->success('添加成功', 'score_list');
			} else {
				return $this->error('添加失败，请重试', 'score_add');
			}
		}
		// $user = UserModel::where('name',session('name'))->find();
		// $pwd = $user->pwd;
		// $this->assign([
		// 	'pwd' => $pwd,
		// ]);

		$sno = input('get.sno');
		if (ScoreModel::where('sno', $sno)->find()) {
			// $this -> assign('sno','1');
			// return $this->fetch('student_list');
			$this->error('不能重复添加');
		}
		$this->assign('sno', $sno);
		return $this->fetch();
	}

	//分数列表
	public function score_list()
	{
		if (empty($_GET['sno'])) {
			$data = ScoreModel::view('student a', 'sno,sname')
				->view('score b', 'id,sno,chinese,mathematics,english,update_time', 'b.sno=a.sno')
				->order('b.id')
				->limit(0)
				->paginate(10);
			$page = $data->render();
			$this->assign('data', $data);
			$this->assign('page', $page);
			foreach ($data as $key);
			if (empty($key['sno'])) {
				return $this->fetch('index/empty');
			}
			return $this->fetch();
		}
		$sno = input('get.sno');
		$data = ScoreModel::view('student a', 'sno,sname')
			->view('score b', 'id,sno,chinese,mathematics,english,update_time', "b.sno=a.sno")
			->where('a.sno', $sno)
			->order('a.sno')
			->limit(0)
			->paginate(3);
		$page = $data->render();
		$this->assign('data', $data);
		$this->assign('page', $page);
		if ($data->isEmpty()) {
			return $this->fetch('index/empty');
		}
		return $this->fetch();
	}

	//删除分数
	public function score_delete()
	{
		$id = input('get.id');
		$user = ScoreModel::get($id);
		$user->delete();
		$this->success('删除成功', 'score_list');
	}

	//修改分数
	public function score_update()
	{
		if (!empty($_POST)) {
			$data = input('post.');
			$id = input('get.id');
			// $old_date = ScoreModel::get($id);
			$re = new ScoreModel();
			$re->save([
				'chinese' => $data['chinese'],
				'mathematics' => $data['mathematics'],
				'english' => $data['english'],
				'semester' => $data['semester']
			], ['id' => $id]);
			if ($re) {
				return $this->success('修改成功', 'score_list');
			} else {
				return $this->error('修改失败，请重试');
			}
		}
		$id = input('get.id');
		$old_data = ScoreModel::get($id);
		$this->assign([
			'id' => $id,
			'chinese' => $old_data['chinese'],
			'mathematics' => $old_data['mathematics'],
			'english' => $old_data['english']
		]);
		return $this->fetch();
	}

	//优秀率及格率查询
	public function score_select_field()
	{
		if (!empty($_POST)) {
		$project = input('post.project');
		// dump($project);
		// die();

		$table1 = ScoreModel::view(['admin_student' => 'a'], 'sclass', 'a.sno=b.sno', 'right')
			->view(['admin_score' => 'b'], $project)
			->field('count(*) as count1')
			->where($project, '>=', '90')
			->group('sclass')
			->buildSql();
		$table2 = ScoreModel::view(['admin_student' => 'a'], 'sclass', 'a.sno=b.sno')
			->view(['admin_score' => 'b'], $project)
			->field('count(*) as count2')
			->where($project, '<', '60')
			->group('sclass')
			->buildSql();
		$table5 = ScoreModel::view(['admin_student' => 'a'], 'sclass', 'a.sno=b.sno')
			->view(['admin_score' => 'b'], $project)
			->field('count(*) as count0')
			->group('sclass')
			->buildSql();
		$data = ScoreModel::withTrashed()->view(['admin_student' => 'a'], 'sno', 'a.sclass = c.sclass')
			->field('c.count1/d.count0 as excellent')
			->field('e.count2/d.count0 as failed')
			->view([$table5 => 'd'], 'count0', 'a.sclass = d.sclass', 'left')
			->view([$table2 => 'e'], 'count2', 'a.sclass = e.sclass', 'left')
			->view([$table1 => 'c'], 'sclass,count1')
			->group('sclass')
			// ->paginate(3)
			// ->fetchSql(true)
			->select();
			// dump($data);
			// die();
		// $page = $data->render();
		// $this->assign('page', $page);
		if($project == 'chinese'){
			$project = '语文';
		}
		elseif($project == 'mathematics'){
			$project = '数学';
		}
		elseif($project == 'english'){
			$project = '英语';
		}
		$this->assign('project', $project);
		$this->assign('data', $data);
		return $this->fetch();
		}
		return $this->fetch('inquire');
	}

	

	//测试方法
	public function test()
	{
		// $test = "'speciality'".','."'物联网'";
		// $a1 = 'speciality';
		// $a2 = '计算机科学与技术';
		// // dump($test);
		// // die;
		// $user = StudentModel::where($a1,$a2)->where('')->where;
		// $user1 = StudentModel::view('student','id,sno,sclass')
		// 				->view('score','sno,chinese,mathematics,english')
		// 				->where('sclass','');
		// $data1 = StudentModel::view('student', 'id,sno,sname,sclass,id+sno as idsno')
		// 	->where('id', '7')
		// 	->select();
		// dump($data1);
		// die();

		// $data1 = StudentModel::count('id');
		// echo $data1;
		// dump($data1);
		// die();

		// select a.sclass,count(chinese)
		// from student a,score
		// where `chinese` > '90'
		// group by 'sclass'

		$sql = 'select a.sclass,count(`chinese`)
		from admin_student a,admin_score b
		where b.chinese > 90
		group by sclass';

		// select a.sclass,count(`chinese`)/count(id),(count(id)-count(chinese))/count(id)
		// from admin_student a,admin_score b
		// where b.chinese > '90'
		// group by sclass


		// select a.sclass,count(`chinese`)/count(a.id),(count(a.id)-count(chinese))/count(a.id)
		// from admin_student a,admin_score b
		// where b.chinese > '90'
		// group by sclass

		// select a.sclass,count(*)
		// from admin_student a,admin_score b
		// where b.chinese > '90' and a.sno=b.sno
		// group by sclass

		// $table1 = ScoreModel::count();

		// $semester = 'chinese';
		// $a4 = '>';
		$project = 'mathematics';
		$table1 = ScoreModel::view(['admin_student' => 'a'], 'sclass', 'a.sno=b.sno', 'right')
			->view(['admin_score' => 'b'], $project)
			->field('count(*) as count1')
			->where($project, '>=', '90')
			->group('sclass')
			->buildSql();
		// 	->fetchSql(true)
		// 	->select();
		// dump($table1);
		// die();

		$table2 = ScoreModel::view(['admin_student' => 'a'], 'sclass', 'a.sno=b.sno')
			->view(['admin_score' => 'b'], $project)
			->field('count(*) as count2')
			->where($project, '<', '60')
			->group('sclass')
			->buildSql();
		// ->fetchSql(true)
		// ->select();
		// dump($table2);
		// die();

		$table5 = ScoreModel::view(['admin_student' => 'a'], 'sclass', 'a.sno=b.sno')
			->view(['admin_score' => 'b'], $project)
			->field('count(*) as count0')
			->group('sclass')
			->buildSql();
		// ->fetchSql(true)
		// ->select();
		// dump($table5);
		// die();

		$table4 = ScoreModel::withTrashed()->view(['admin_student' => 'a'], 'sno', 'a.sclass = c.sclass')
			->field('c.count1/d.count0 as 优秀率')
			->field('e.count2/d.count0 as 不及格率')
			->view([$table5 => 'd'], 'count0', 'a.sclass = d.sclass', 'left')
			->view([$table2 => 'e'], 'count2', 'a.sclass = e.sclass', 'left')
			->view([$table1 => 'c'], 'sclass,count1')
			->group('sclass')
			// ->fetchSql(true)
			->select();
		dump($table4);
		die();




		// $table3 = ScoreModel::view([$table1 => 'a'], 'sclass,count(*)', 'a.sclass = b.sclass')
		// 	->view([$table2 => 'b'], 'count(*)')
		// 	->fetchSql(true)
		// 	->select();

		// $table3 = ScoreModel::view('student','sclass','student.sno=score.sno')
		// ->view('score','*')
		// ->field('count(score.id) as count0')
		// ->view([$table1 => 'a'] ,['count(*)' => 'count1'])
		// // ->view([$table2 => 'd'],['count(*)' => 'count2'])
		// // ->field('count(*)/count0 as 优秀率')
		// ->group('sclass')
		// 	->fetchSql(true)
		// ->select();

		// ->buildSql();
		// $user = new ScoreModel;
		// $user-> getLastSql($data);
		// dump($table3);
		// die();


		// ->having('count(chinese)>2')






		// dump($data);
		// die();
		// ->select();
		// ->semester($semester)

		// foreach ($data as $key);
		// dump($key['sclass']);
		// die();


		$this->assign('data', $data);
		return $this->fetch();


		$data2 = ScoreModel::count();
		$excellent = $data / $data2;
		$new = sprintf("%.2f", $excellent * 100) . '%';
		dump($new);
		// dump($user);
		// die();
	}
}


// SELECT `a`.*,b.count(*) FROM
//  ( SELECT `student`.`sclass`,`score`.`chinese`,count(*)
//   FROM `admin_score` `score` INNER JOIN `admin_student` `student` 
//   ON `student`.`sno`=`score`.`sno`
//    WHERE (  `chinese` >= 90 ) 
//    AND `score`.`delete_time` IS NULL GROUP BY `sclass` )
//  `b` INNER JOIN `student` `a` ON `a`.`sclass`=`b`.`sclass` 

//  SELECT `a`.*,b.sclass FROM
//  ( SELECT `student`.`sclass`,`score`.`chinese`,count(*)
//   FROM `admin_score` `score` INNER JOIN `admin_student` `student` 
//   ON `student`.`sno`=`score`.`sno`
//    WHERE (  `chinese` >= 90 ) 
//    AND `score`.`delete_time` IS NULL GROUP BY `sclass` )
//  `b` INNER JOIN `admin_student` `a` ON `a`.`sclass`=`b`.`sclass` 

// SELECT `a`.`sno`,c.count1/d.count0 as 及格率,`c`.`sclass`,`c`.`count1`,`d`.`count0` FROM
// ( SELECT `a`.`sclass`,`b`.`chinese`,count(*) as count0 FROM `admin_score` `b` INNER JOIN `admin_student` `a` ON `a`.`sno`=`b`.`sno` WHERE `b`.`delete_time` IS NULL GROUP BY `sclass` ) `d` 
// INNER JOIN `admin_student` `a` ON `a`.`sclass`=`d`.`sclass` INNER JOIN 
// ( SELECT `a`.`sclass`,`b`.`chinese`,count(*) as count1 FROM `admin_score` `b` INNER JOIN `admin_student` `a` ON `a`.`sno`=`b`.`sno` WHERE (  `chinese` >= 90 ) AND `b`.`delete_time` IS NULL GROUP BY `sclass` ) `c`
// ON `c`.`sclass`=`a`.`sclass` GROUP BY `sclass`
