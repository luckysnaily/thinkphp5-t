<?php 

namespace app\admin\model;

use think\Model;

class Article extends Model
{
	protected $pk = 'article_id';

	// 时间戳自动维护
	protected $autoWriteTimestamp = true;
}