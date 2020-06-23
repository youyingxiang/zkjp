<?php
namespace app\admin\validate;

use think\Validate;

class Post extends Validate
{
    protected $rule = [
        'job_name' => 'require|max:128',
        'order_key' => 'require|integer',
        'state' => 'require|in:0,1',
    ];
    protected $message = [
        'job_name.require'  => '{%job_name_r}',
        'job_name.max' => '{%job_name_m}', 
        'state' => '{%status_val}',
        'order_key.require' => '{%order_key_r}',
        'order_key.integer' => '{%order_key_i}',
    ];
    protected $scene = [
        'add'   => ['job_name','state', 'order_key'],
        'edit'  => ['job_name', 'state','order_key'],
        'job_name' => ['job_name'],
        'state' => ['state'],
        'describe' => ['describe'],
        'order_key' => ['order_key'],
    ];
}