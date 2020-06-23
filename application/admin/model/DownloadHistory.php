<?php
namespace app\admin\model;
use think\Db;
use think\Model;

class DownloadHistory extends Model
{
	protected $updateTime = false;
	protected $autoWriteTimestamp = true;
	protected function DownFileHistory()
	{
		return $this->hasOne('File','id','file_id');
	}
}