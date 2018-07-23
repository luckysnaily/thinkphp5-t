<?php 

namespace app\admin\controller;

use think\Controller;

class IndexController extends Controller
{

	public function index()
	{
		return $this->fetch();
	}


	public function left()
	{
		return $this->fetch();
	}


	public function top()
	{
		return $this->fetch();
	}


	public function main()
	{
		return $this->fetch();
	}

	public function login()
	{
		return $this->fetch();
	}

	public function logout()
	{
		return 'logout';
	}

	public function test()
	{
		echo url('left', ['id' => 1, 'name' => 'aaa']);
	}
}