<?php
namespace app\index\controller;

use think\Request;

class Index
{
	/**
     * @var \think\Request Request实例
     */
    protected $request;
    
    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request)
    {
		$this->request = $request;
    }
    
    public function index()
    {

		echo $this->request->baseFile();
		$arr = input(''); 

    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
    public function checkLogin(){
      echo "123";
    }
}
