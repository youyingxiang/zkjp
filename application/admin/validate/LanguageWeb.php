<?php
namespace app\admin\validate;

use think\Validate;

class LanguageWeb extends Validate
{
    protected $rule = [
        'name' => 'require|max:128',
        'url'  => 'max:255',
        'order_key' => 'require|integer',
        'state' => 'require|in:0,1',
    ];
    protected $message = [
        'name.require'  => '{%name_r}',
        'name.max' => '{%name_m}', 
        'state' => '{%state_val}',
        'url.max' => '{%url_m}',
        'order_key.require' => '{%order_key_r}',
        'order_key.integer' => '{%order_key_i}',
    ];
    protected $scene = [
        'add'   => ['name','state','order_key','url'],
        'edit'  => ['name', 'state','order_key','url'],
        'name' => ['name'],
        'state' => ['state'],
        'url' => ['url'],
        'order_key' => ['order_key'],
    ];
}