<?php 

namespace app\admin\controller;

use think\Controller;

class CommonController extends Controller 
{
	public function _initialize()
	{
		if ( !session('user_id') ) {
			// halt( url('public/login') );
			$this->success("登录后再试", url('admin/public/login'));
		}
	}

	public function uploadImg($fileName)
	{
		// halt($fileName);
		$ori_img = '';	// 原图路径
		$thumb_img = '';	//	存储缩略图的路径
		// 判断是否有文件上传
		if ( $file = request()->file($fileName) ) {
			# 定义上传文件的目录
			$uploadDir = './upload/';
			# 定义上传文件的验证规则
			$condition = [
				'size' => 1024*1024*2,
				'ext' => 'png,jpg,jpeg,gif',
			];
			# 验证并上传文件
			$info = $file->validate($condition)->move($uploadDir);
			// halt($info);
			if ( $info ) {
				$ori_img = $info->getSaveName();
				$image = \think\Image::open( './upload/' . $ori_img );
				$arr_path = explode('\\', $ori_img);
				$thumb_img = $arr_path[0] . '/thumb_' . $arr_path[1];
				$image->thumb(150,150,2)->save('./upload/' . $thumb_img);
				return ['ori_img' => $ori_img, 'thumb_img' => $thumb_img];
			}else {
				$this->error( $file->getError() );
			}
		}
	}
}