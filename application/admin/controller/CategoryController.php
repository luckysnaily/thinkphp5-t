<?php

namespace app\admin\controller;

use think\Request;
use think\Validate;
use app\admin\model\Category;

class CategoryController extends CommonController
{

	/**
	 * 分类首页
	 */
	public function index()
	{
		$catModel = new Category;

		$data = $catModel
				->field('t1.*, t2.cat_name p_name')
				->alias('t1')
				->join('tp_category t2', 't1.pid = t2.cat_id', 'left')
				->select();
		# 调用分类模型的getSonCats()方法进行无限级分类处理
		$cats = $catModel->getSonCats($data);

		# 输出模板视图,并返回数据
		return $this->fetch('', ['cats'=>$cats]);

	}


	/**
	 * 添加分类
	 */
	public function add()
	{
		$catModel = new Category();
		# 判断请求是否为破post
		if ( request()->isPost() ) {
			# 接受post数据
			$postData = input('post.');

			# 使用验证器验证数据
			$result = $this->validate($postData, 'Category.add', [], true);

			if ( $result !== true ) {
				$this->error( implode(',', $result) );
			}

			// $rule = [
			// 	'cat_name' => 'require|unique:category',
			// 	'pid' => 'require',
			// ];
			// $message = [
			// 	'cat_name.require' => '分类名不能为空',
			// 	'cat_name.unique' => '分类名称重复',
			// 	'pid.require' => '请选择父分类',
			// ];
			// # 实例化验证器对象
			// $validate = new Validate($rule, $message);
			// # 使用验证器对象的check()方法和batch()方法验证数据
			// $result = $validate->batch()->check($postData);
			// if ( !$result ) {
			// 	# 验证没通过
			// 	$this->error( implode(',', $validate->getError()) );
			// }

			
			#验证通过之后进行数据入库
			if ( $catModel->save($postData) ) {
				$this->success('入库成功', url('admin/category/index'));
			}else {
				$this->error('入库失败');
			}
		}

		// 获取所有分类
		$data = $catModel->select();
		// 对分类数据进行递归处理(含层级关系缩进)
		$cats = $catModel->getSonCats($data);
		return $this->fetch('', ['cats'=>$cats]);
	}


	/**
	 * 编辑分类
	 */
	public function upd()
	{
		$catModel = new Category();

		if ( request()->isPost() ) {
			# 接受数据
			$postData = input('post.');

			# 验证数据   
			# Category.upd 表示应用Category验证器下的upd场景的验证规则
			$result = $this->validate($postData, 'Category.upd', [], true);
			if ( true !== $result ) {
				$this->error( implode(',', $result) );
			}

			if ( $catModel->update($postData) ) {
				$this->success('编辑成功', url('admin/category/index'));
			}else {
				$this->error('编辑失败');
			}
		}

		# 接受参数cat_id,取出当前分类的数据
		$cat_id = input('cat_id');
		$catInfo = $catModel->find($cat_id);

		$data = $catModel->select();
		# 无极限分类处理
		$cats = $catModel->getSonCats($data);
		return $this->fetch('', [
			'cats' => $cats, 
			'catInfo' => $catInfo,
		]);
	}

}