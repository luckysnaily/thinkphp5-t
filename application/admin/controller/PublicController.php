<?php 

namespace app\admin\controller;

use think\Controller;
use app\admin\model\User;
use think\Validate;

class PublicController extends Controller 
{
	public function login()
	{
		# 判断是否post请求
		if ( request()->isPost() ) {
			# 接受post参数
			$postData = input('post.');
			# 验证数据是否合法 (验证器验证)
			# 验证规则
			$rule = [
				'username' => 'require|length:4,8',
				'password' => 'require',
				'captcha' => 'require|captcha',
			];
			# 验证的错误信息
			$message = [
				'username.require' => '用户名不能为空',
				'username.length' => '用户名长度为4-8位',
				'password.require' => '密码不能为空',
				'captcha.require' => '验证码必填',
				'captcha.captcha' => '验证码错误',
			];

			# 实例化验证器对象进行验证
			$validate = new Validate($rule, $message);
			# batch() 表示批量验证
			$result = $validate->batch()->check($postData);
			if ( !$result ) {
				$this->error( implode(',', $validate->getError()) );
			}

			# 验证用户名和密码是否正确
			$userModel = new User();
			$flag = $userModel->checkUser($postData['username'], $postData['password']);
			if ( $flag ) {
				# 跳转到后台首页
				$this->redirect('admin/index/index');
			}else {
				$this->error('用户名或密码错误');
			}
		}
		return $this->fetch();
	}


	public function logout()
	{
		# 清除session信息
		session(null);
		return $this->redirect('login');
	}
}