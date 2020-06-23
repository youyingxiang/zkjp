<?php
namespace app\admin\validate;

use think\Validate;

class DutyCategory extends Validate
{
    protected $rule = [
        'name' => 'require|unique:category|max:128',
        'order_key' => 'require|integer',
    ];
    protected $message = [
        'name.require'              => '{%name_r}',
        'name.unique'               => '{%name_u}',
        'name.max'                  => '{%name_m}', 
        'order_key.require'         => '{%order_key_r}',
        'order_key.integer'         => '{%order_key_i}',
    ];
    protected $scene = [
        'add'   => ['name','order_key'],
        'edit'  => ['name','order_key'],
        'name'  => ['name'],
        'state' => ['state'],
        'order_key' => ['order_key'],
    ];
}