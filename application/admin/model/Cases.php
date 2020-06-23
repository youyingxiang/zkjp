<?php
namespace app\admin\model;
use think\Model;
class Cases extends Model
{
    protected $updateTime = false;
    protected $autoWriteTimestamp = true;
    protected function setNeedAttr($value)
    {
        $data = serialize($value);
        return $data;
    }
    protected function getNeedAttr($value)
    {
        $data = unserialize($value);
        return $data;
    }
    protected function CasesScheme()
    {
        return $this->hasOne('scheme','id','scheme_id');
    }
    public function casesProduct()
    {
        return $this->belongsToMany('product','product_cases','product_id','cases_id');
    }
    public function casesSelf()
    {
        return $this->belongsToMany('cases','cases_self','cases_id','cases_id_master');
    }
    public function casesFile()
    {
        return $this->belongsToMany('file','cases_file','file_id','cases_id');
    }
}