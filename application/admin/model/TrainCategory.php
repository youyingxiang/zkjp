<?php
namespace app\admin\model;

use think\Model;

class TrainCategory extends Model
{
    protected $autoWriteTimestamp = true;

    public function traincatFile()
    {
        return $this->belongsToMany('file','traincatFile','file_id','traincat_id');
    }
    public function traincatVideo()
    {
        return $this->belongsToMany('video','traincatVideo','video_id','traincat_id');
    }
}