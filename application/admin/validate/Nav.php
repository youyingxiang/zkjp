<?php
namespace app\admin\validate;

use think\Validate;

class Nav extends Validate
{
    protected $rule = [
        'parent_id' => 'require|integer',
        'name' => 'require|unique:nav|max:128',
        'order_key' => 'require|integer',
    ];
    protected $message = [
        'parent_id.require'         => '{%parent_id_r}',
        'parent_id.integer'         => '{%parent_id_i}',
        'name.require'              => '{%name_r}',
        'name.unique'               => '{%name_u}',
        'name.max'                  => '{%name_m}', 
        'order_key.require'         => '{%order_key_r}',
        'order_key.integer'         => '{%order_key_i}',

    ];
    protected $scene = [
        'add'   => ['parent_id', 'name','order_key'],
        'edit'  => ['parent_id', 'name','order_key'],
        'name' => ['name'],
        'url'  => ['url'],
        'order_key' => ['order_key'],
    ];
}