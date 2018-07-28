<?php 

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Category;
use app\admin\model\Article;


class ArticleController extends CommonController
{	

	public function getContent()
	{
		if ( request()->isAjax() ) {
			$article_id = input('article_id');
			$content = Article::where(['article_id' => $article_id])->value('content');
			return json(['content'=>$content]);
		}
	}

	public function del()
	{
		$article_id = input('article_id');
		$oldObj = Article::get($article_id);
		if ( $oldObj['ori_img'] ) {
			unlink('./upload/' . $oldObj['ori_img']);
			unlink('./upload/' . $oldObj['thumb_img']);
		}
		if ( $oldObj->delete() ) {
			$this->success('删除成功', url('admin/article/index'));
		}else {
			$this->error('删除失败');
		}
	}

	public function upd()
	{
		$artModel = new Article();

		if ( request()->isPost() ) {
			# 接受post参数
			$postData = input('post.');
			# 验证器验证
			$result = $this->validate($postData, 'Article.upd', [], true);
			# 验证不通过
			if ( $result !== true ) {
				$this->error( implode(',', $result) );
			}
			$path = $this->uploadImg('img');
			// halt($path);
			if ( $path ) {
				// 删除原来文章的原图和缩略图
				// 获取到图片的原图路径和缩略图路径
				$oldImg = $artModel->find( $postData['article_id'] );
				if ( $oldImg['ori_img'] ) {
					unlink('./upload/' . $oldImg['ori_img']);
					unlink('./upload/' . $oldImg['thumb_img']);
				}
				$postData['ori_img'] = $path['ori_img'];
				$postData['thumb_img'] = $path['thumb_img'];
			}
			# 编辑入库
			if ( $artModel->update($postData) ) {
				$this->success('编辑成功', url('admin/article/index'));
			}else {
				$this->error('编辑失败');
			}
		}

		$catModel = new Category();
		$article_id = input('article_id');
		// 取出当前文章的数据,分配的模板中
		$art = $artModel->find($article_id);
		// 取出所有分类
		$cats = $catModel->getSonCats( $catModel->select() );
		return $this->fetch('', ['art' => $art, 'cats' => $cats]);
	}

	public function index()
	{
		# 获取文章数据
		$articles = Article::alias('a')
			->field('a.*, c.cat_name')
			->join('tp_category c', 'a.cat_id = c.cat_id', 'left')
			->paginate(3);
		return $this->fetch('', ['arts' => $articles]);
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
					'ext' => 'png,jpg,gif,jpeg',
				];
				// 验证并进行上传
				$info = $file->validate($condition)->move($uploadDir);
				// 判断文件上传是否成功
				if ( $info ) {
					$postData['ori_img'] = $info->getSaveName();
					# 生成缩略图,文件名加 thumb_ 前缀,并另存到原图所在目录下
					# 实例化图像类,打开上传处理后的图片文件
					$image = \think\Image::open('./upload/' . $postData['ori_img']);
					$arr_path = explode( '\\', $postData['ori_img'] );
					$thumb_path = $arr_path[0] . '/thumb_' . $arr_path[1];
					# 生成缩略图并保存
					$image->thumb(150,150,2)->save('./upload/' . $thumb_path);
					# 保存缩略图的路径到数据表字段
					$postData['thumb_img'] = $thumb_path;
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