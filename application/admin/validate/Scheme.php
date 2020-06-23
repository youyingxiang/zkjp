<?php
namespace app\admin\validate;

use think\Validate;

class Scheme extends Validate
{
    protected $rule = [
        'name'                  => 'require|max:128',
        'scheme_img'            => 'max:255',
        'advantage_img'         => 'max:255',
        'solution_img'          => 'max:255',
        'seo_keyword'           => 'max:200',
        'seo_des'               => 'max:500',
        'order_key'             => 'require|integer',
    ];
    protected $message = [
        'name.require'          => '{%name_r}',
        'name.max'              => '{%name_m}',
        'scheme_img.max'        => '{%scheme_img_m}',
        'advantage_img.max'     => '{%advantage_img_m}',
        'solution_img.max'      => '{%solution_img_m}',
        'seo_keyword.max'       => '{%seo_keyword_m}',
        'seo_des.max'           => '{%seo_des_m}', 
        'order_key.require'     => '{%order_key_r}',
        'order_key.integer'     => '{%order_key_i}',       
    ];
    protected $scene = [
        'add'   => ['name','scheme_img','advantage_img','solution_img','seo_keyword','seo_des','order_key'],
        'edit'  => ['name','scheme_img','advantage_img','solution_img','seo_keyword','seo_des','order_key'],
        'name'  => ['name'],
        'seo_keyword' => ['seo_keyword'],
        'seo_des' => ['seo_des'],
        'state' => ['state'],
        'order_key' => ['order_key'],
    ];
}