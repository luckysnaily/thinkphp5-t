<?php 

namespace app\admin\model;

use think\Model;

class User extends Model
{
	/**
	 * 验证用户登录
	 * @param  [type] $username 用户名
	 * @param  [type] $password 密码
	 * @return 成功返回true 失败返回false
	 */
	public function checkUser( $username, $password )
	{
		$where = [ 
			'username' => $username,
			'password' => md5( $password.config('password_salt') ),
		];
		$userInfo = $this->where($where)->find();
		if ( $userInfo ) {
			// session_start();
			# 将用户信息存储到session中
			session('user_id', $userInfo['user_id']);
			session('username', $userInfo['username']);
			return true;
		}else {
			return false;
		}
	}
}