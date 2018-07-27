<?php 

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Category;
use app\admin\model\Article;


class ArticleController extends Controller
{
	public function index()
	{
		echo '文章首页--------------假装此页有列表';
	}


	public function add()
	{
		// 取出分类数据并进行无极限处理
		$catModel = new Category();
		$articleModel = new Article(); 

		if ( request()->isPost() ) {
			$postData = input('post.');
			$result = $this->validate($postData, 'Article.add', [], true);

			if ( $result !== true ) {
				$this->error( implode(',', $result) );
			}

			# 判断是否有文件上传
			if ( $file = request()->file('img') ) {
				// 定义上传文件的保存路径(./相对于入口文件index.php)
				$uploadDir = './upload/';
				// 定义文件上传的验证规则
				$condition = [
					'size' => 1024*1024*2, # size的单位为字节 2M
					'ext' => 'png, jpg, gif, jpeg',
				];
				// 验证并进行上传
				$info = $file->validate($condition)->move($uploadDir);
				// 判断文件上传是否成功
				if ( $info ) {
					$postData['ori_img'] = $info->getSaveName();
				}else {
					$this->error( $file->getError() );
				}
			}

			if ( $articleModel->save($postData) ) {
				$this->success('入库成功', url('admin/article/index'));
			}else {
				$this->error('入库失败');
			}
		}

		$cats = $catModel->getSonCats( $catModel->select() );
		return $this->fetch( '', ['cats' => $cats] );
	}
}