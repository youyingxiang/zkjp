<?php
namespace app\admin\model;

use think\Model;

class Scheme extends Model
{
    protected $autoWriteTimestamp = true;
    public function schemeProduct()
    {
        return $this->belongsToMany('product','product_scheme','product_id','scheme_id');
    }
    public function schemeCases()
    {
        return $this->belongsToMany('Cases','cases_scheme','cases_id','scheme_id');
    }
    public function schemeFile()
    {
        return $this->belongsToMany('file','scheme_file','file_id','scheme_id');
    }
}