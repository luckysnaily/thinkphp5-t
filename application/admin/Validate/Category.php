<?php 

namespace app\admin\validate;

use think\Validate;

class Category extends Validate 
{
	# 定义验证规则
	protected $rule = [
		# 需要验证的字段名 => '验证规则1|验证规则2...'
		'cat_name' => 'require|unique:category',
		'pid' => 'require',
	];

	# 定义错误提示信息
	protected $message = [
		'cat_name.require' => '分类名称不能为空',
		'cat_name.unique' => '分类名称重复',
		'pid.require' => '请选择父分类',
	];

	# 定义验证的场景
	protected $scene = [
		# 场景名 => ['需要验证的字段名称1' => '规则1|规则2...', '需要验证字段名称2' => '规则1|规则2...' ]  规则缺省表示验证$rule属性下定义的对应字段的所有验证规则

		'add' => ['cat_name', 'pid'],
		'upd' => ['cat_name' => 'require', 'pid'],
	];
}