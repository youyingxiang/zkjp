<?php
namespace app\admin\model;
use think\Model;
class Product extends Model
{
    protected $autoWriteTimestamp = true;
    protected function setProductTraitAttr($value)
    {
        $data = serialize($value);
        return $data;
    }
    protected function getProductTraitAttr($value)
    {
        $data = unserialize($value);
        return $data;
    }
    protected function setProductMaterialAttr($value)
    {
        $data = serialize($value);
        return $data;
    }
    protected function getProductMaterialAttr($value)
    {
        $data = unserialize($value);
        return $data;
    }
    protected function setFlagAttr($value)
    {
        $value = json_encode($value);
        return $value;
    }
    protected function getFlagAttr($value)
    {
        $value = json_decode($value);
        return $value;
    }
    public function productImg()
    {
        return $this->hasMany('product_img','product_id','id');
    }
    public function productCategory()
    {
        return $this->belongsToMany('category','product_category','category_id','product_id');
    }
    public function productFile()
    {
        return $this->belongsToMany('file','product_file','file_id','product_id')->where('state',1);
    }
    public function productSelf()
    {
        return $this->belongsToMany('product','product_self','product_id','product_id_master');
    }
    public function productCases()
    {
        return $this->belongsToMany('cases','product_cases','cases_id','product_id');
    }
    public function productScheme()
    {
        return $this->belongsToMany('scheme','product_scheme','scheme_id','product_id');
    }
}
