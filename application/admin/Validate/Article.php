<?php

namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
	protected $rule = [
		'title' => 'require|unique:article',
		'cat_id' => 'require',
	];

	protected $message = [
		'title.require' => '标题不能为空',
		'title.unique' => '标题名重复',
		'cat_id.require' => '文章分类不能为空', 
	];

	protected $scene = [
		'add' => ['title', 'cat_id'],
		'upd' => ['title', 'cat_id'],
	];
}