<?php
namespace app\admin\validate;

use think\Validate;

class Recruit extends Validate
{
    protected $rule = [
        'name' => 'require|max:128',
        'order_key' => 'require|integer',
        'state' => 'require|in:0,1',
        'num'   => 'require|integer',
        'url'   => 'max:255',
    ];
    protected $message = [
        'name.require'  => '{%name_r}',
        'name.max' => '{%name_m}', 
        'state' => '{%status_val}',
        'order_key.require' => '{%order_key_r}',
        'order_key.integer' => '{%order_key_i}',
        'num.require' => '{%num_r}',
        'num.integer' => '{%num_i}',
        'url.max' => '{%url_m}',
    ];
    protected $scene = [
        'add'   => ['name','state', 'order_key','num','url'],
        'edit'  => ['name', 'state','order_key','num','url'],
        'name' => ['name'],
        'emergency' => ['emergency'],
        'state' => ['state'],
        'num' => ['num'],
        'order_key' => ['order_key'],
    ];
}