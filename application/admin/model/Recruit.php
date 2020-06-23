<?php
namespace app\admin\model;
use think\Db;
use think\Model;
class Recruit extends Model
{
    protected $autoWriteTimestamp = true;
    protected function RecruitPost()
	{
		return $this->hasOne('Post','id','job_id');
	}
	protected function getWorkNatureAttr($value)
	{
		$data = config('work_nature');
		return $data[$value];
	}
	protected function RecruitRegionp()
	{
		return $this->hasOne('region','id','province');
	}
	protected function RecruitRegionc()
	{
		return $this->hasOne('region','id','city');
	}
	protected function setTypeAttr($value)
	{
		$value = json_encode($value);
		return $value;
	}
	protected function getTypeAttr($value)
	{
		$value = json_decode($value);
		return $value;
	}

	public function get_work_address($id)
	{
		$data = $this->where(['type'=>['like','%'.$id.'%'],'state'=>['eq',1]])->order('order_key asc')->select();
		$res = [];
		foreach ($data as $key => $value) {
			$province = '';
			$city = '';
			$res[$value['province']] = $value['province'];
		}
		return $res;
	}

}