<?php
namespace app\admin\model;

use think\Model;

class Duty extends Model
{
	protected $autoWriteTimestamp = true;
	protected function dutyCategory()
	{
		return $this->hasOne('duty_category','id','typeid');
	}
}