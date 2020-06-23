<?php
namespace app\admin\model;

use think\Model;

class File extends Model
{
	protected $autoWriteTimestamp = true;
	protected $updateTime = false;               //加

	protected function FileCategory()
	{
		return $this->hasOne('Category','id','cate_id');
	}
		protected function setUpdateTimeAttr($value)                  //加
	{
		!empty($value)&&$value = strtotime($value);
		return $value;
	}
	protected function getUpdateTimeAttr($value)
	{
		!empty($value)&&$value = date('Y-m-d',$value);
		return $value;
	}                                                            //加
}