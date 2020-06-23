<?php
namespace app\admin\validate;

use think\Validate;

class File extends Validate
{
    protected $rule = [
        'title' => 'require|unique:file|max:128',
        'order_key' => 'require|integer',
    ];
    protected $message = [
        'title.require'             => '{%title_r}',
        'title.unique'              => '{%title_u}',
        'title.max'                 => '{%title_m}', 
        'order_key.require'         => '{%order_key_r}',
        'order_key.integer'         => '{%order_key_i}',
    ];
    protected $scene = [
        'add'   => ['title','order_key'],
        'edit'  => ['title','order_key'],
        'title' => ['title'],
        'state' => ['state'],
        'file_name' => ['file_name'],
        'file_size' => ['file_size'],
        'order_key' => ['order_key'],
    ];
}