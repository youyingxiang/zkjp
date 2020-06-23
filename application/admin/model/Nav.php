<?php
namespace app\admin\model;
use think\Model;

class Nav extends Model
{
	protected $autoWriteTimestamp = true;
	
	public function getTree($order,$where='')
	{
		$data = $this->where($where)->order($order)->select();
		return $this->_reSort($data);
	}
	
	public function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$v['level'] = $level;
				$ret[] = $v;
				$this->_reSort($data, $v['id'], $level+1, FALSE);
			}
		}
		return $ret;
	}

	public function getChildren($id)
	{
		$data = $this->select();
		return $this->_children($data, $id);
	}
	
	private function _children($data, $parent_id=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$ret[] = $v['id'];
				$this->_children($data, $v['id'], FALSE);
			}
		}
		return $ret;
	}

	protected function getShowPositionAttr($value,$data)
	{
		$turnArr = [1=>lang('show_position_1'), 2=>lang('show_position_2'), 3=>lang('show_position_3'),4=>lang('show_position_4')];
        return $turnArr[$data['show_position']];
	}

}