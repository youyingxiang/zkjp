<?php
namespace app\admin\validate;

use think\Validate;

class News extends Validate
{
    protected $rule = [
        'title'                 => 'require|max:200',
        'jumplink'              => 'max:200',
        'img'                   => 'max:255',
        'author'                => 'max:255',
        'source'                => 'max:255',
        'seo_keyword'           => 'max:200',
        'seo_des'               => 'max:500',
        'click'                 => 'require|integer',
    ];
    protected $message = [
        'title.require'         => '{%title_r}',
        'title.max'             => '{%title_m}',
        'jumplink.max'          => '{%jumplink_m}',
        'img.max'               => '{%img_m}',
        'author.max'            => '{%author_m}',
        'source.max'            => '{%source_m}',
        'seo_keyword.max'       => '{%seo_keyword_m}',
        'seo_des.max'           => '{%seo_des_m}',
        'click.require'         => '{%click_r}',
        'click.integer'         => '{%click_i}',             
    ];
    protected $scene = [
        'add'   => ['title', 'jumplink','img','author','source','seo_keyword','seo_des','click'],
        'edit'  => ['title', 'jumplink','img','author','source','seo_keyword','seo_des','click'],
        'title' => ['title'],
        'jumplink'  => ['jumplink'],
        'img' => ['img'],
        'author' => ['author'],
        'source' => ['source'],
        'seo_keyword' => ['seo_keyword'],
        'seo_des' => ['seo_des'],
        'click' => ['click'], 
        'state' => ['state'],
    ];
}