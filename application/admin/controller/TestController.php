<?php 

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Category;

class TestController extends Controller
{

	public function model()
	{
		echo md5('123456'.config('password_salt'));die();
		// 实例化模型
		$catModel = new Category();
		$data = [
			'cat_name' => '足球',
			'pid' => '1'
		];
		dump( $catModel->save($data) );
		dump( $catModel->cat_id );
	}

	public function index()
	{
		dump( Db::table("tp_category")->select() );
	}
}