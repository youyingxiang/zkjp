<?php
namespace app\admin\model;

use think\Model;

class News extends Model
{
	protected $autoWriteTimestamp = true;
	protected $updateTime = false;               //加
	protected function setFlagAttr($value)
	{
		if(!empty($value)&&is_array($value))$value = implode(',',$value);else $value = '';	
		return $value;
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
	protected function getFlagAttr($value)
	{
		if(!empty($value))$value = explode(',',$value);	
		return $value;
	}
	protected function newsCategory()
	{
		return $this->hasOne('Category','id','typeid');
	}
}