<?php
namespace app\admin\validate;

use think\Validate;

class OnlineStore extends Validate
{
    protected $rule = [
        'title' => 'require|max:128',
        'desc' => 'require|max:500',
        'url' => 'max:500',
        'order_key' => 'require|integer',
    ];
    protected $message = [
        'title.require'             => '{%title_r}',
        'title.max'                 => '{%title_m}',
        'desc.require'              => '{%desc_r}',
        'desc.max'                  => '{%desc_m}',
        'url.max'                   => '{%url_m}',
        'order_key.require'         => '{%order_key_r}',
        'order_key.integer'         => '{%order_key_i}',
    ];
    protected $scene = [
        'add'   => ['title','desc','url','order_key'],
        'edit'  => ['title','desc','url','order_key'],
        'title' => ['title'],
        'desc'  => ['desc'],
        'url'   => ['url'],
        'state' => ['state'],
        'order_key' => ['order_key'],
    ];
}