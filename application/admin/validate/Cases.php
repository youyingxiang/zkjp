<?php
namespace app\admin\validate;

use think\Validate;

class Cases extends Validate
{
    protected $rule = [
        'name'                  => 'require|max:128',
        'img'                   => 'max:255',
        'img_nav'               => 'max:255',
        'imple_img'             => 'max:255',
        'seo_keyword'           => 'max:200',
        'seo_des'               => 'max:500',
        'order_key'             => 'require|integer',
    ];
    protected $message = [
        'name.require'          => '{%name_r}',
        'name.max'              => '{%name_m}',
        'img.max'               => '{%img_m}',
        'img_nav.max'           => '{%img_nav_m}',
        'imple_img.max'         => '{%imple_img_m}',
        'seo_keyword.max'       => '{%seo_keyword_m}',
        'seo_des.max'           => '{%seo_des_m}', 
        'order_key.require'     => '{%order_key_r}',
        'order_key.integer'     => '{%order_key_i}',       
    ];
    protected $scene = [
        'add'   => ['name','img','img_nav','imple_img','seo_keyword','seo_des','order_key'],
        'edit'  => ['name','img','img_nav','imple_img','seo_keyword','seo_des','order_key'],
        'name' => ['name'],
        'img' => ['img'],
        'imple_img'=> ['imple_img'],
        'is_top' => ['is_top'],
        'img_nav' => ['img_nav'],
        'seo_keyword' => ['seo_keyword'],
        'seo_des' => ['seo_des'],
        'state' => ['state'],
        'order_key' => ['order_key'],
    ];
}