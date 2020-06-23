<?php
namespace app\admin\validate;

use think\Validate;

class Duty extends Validate
{
    protected $rule = [
        'title'                 => 'require|max:200',
        'img'                   => 'max:255',
        'seo_keyword'           => 'max:200',
        'seo_des'               => 'max:500',
    ];
    protected $message = [
        'title.require'         => '{%title_r}',
        'title.max'             => '{%title_m}',
        'img.max'               => '{%img_m}',
        'seo_keyword.max'       => '{%seo_keyword_m}',
        'seo_des.max'           => '{%seo_des_m}',           
    ];
    protected $scene = [
        'add'   => ['title','img','seo_keyword','seo_des','click'],
        'edit'  => ['title','img','seo_keyword','seo_des','click'],
        'title' => ['title'],
        'img' => ['img'],
        'seo_keyword' => ['seo_keyword'],
        'seo_des' => ['seo_des'],
        'state' => ['state'],
    ];
}