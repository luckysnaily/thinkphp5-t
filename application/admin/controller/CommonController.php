<?php 

namespace app\admin\controller;

use think\Controller;

class CommonController extends Controller 
{
	public function _initialize()
	{
		if ( !session('user_id') ) {
			$this->success("登录后再试", url('login'));
		}
	}
}