<?php 

namespace app\admin\model;

use think\Model;

class Category extends Model
{
	# 指定档期模型的主键字段
	protected $pk = "cat_id";

	# 时间戳自动维护
	protected $autoWriteTimestamp = true;

	# 当时间字段不为create_time和update_time时,通过以下属性指定
	// protected $createTime = 'create_at';
	// protected $updateTime = 'update_at';
	
	public function getSonCats($data, $pid = 0, $level = 1)
	 {
	 	static $result = [];
	 	foreach ($data as $v) {
	 		# 第一次循环一定先找到pid=0的顶级
	 		if ( $v['pid'] == $pid ) {
	 			// level: 分类层级
	 			$v['level'] = $level;
	 			$result[] = $v;
	 			$this->getSonCats($data, $v['cat_id'], $level + 1);
	 		}
	 	}
	 	# 返回递归处理好后的数据
	 	return $result;
	 } 

} 