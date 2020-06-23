<?php
namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'parent_id' => 'require|integer',
        'name' => 'require|max:128',
        'order_key' => 'require|integer',
        'page'  => 'require|integer',
    ];
    protected $message = [
        'parent_id.require'         => '{%parent_id_r}',
        'parent_id.integer'         => '{%parent_id_i}',
        'name.require'              => '{%name_r}',
        'name.max'                  => '{%name_m}', 
        'order_key.require'         => '{%order_key_r}',
        'order_key.integer'         => '{%order_key_i}',
        'page.require'              => '{%page_r}',
        'page.integer'              => '{%page_i}',


    ];
    protected $scene = [
        'add'   => ['parent_id', 'name','order_key','page'],
        'edit'  => ['parent_id', 'name','order_key','page'],
        'name'  => ['name'],
        'page'  => ['page'],
        'state' => ['state'],
        'order_key' => ['order_key'],
    ];
}