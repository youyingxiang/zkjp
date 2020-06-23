<?php
namespace app\admin\validate;

use think\Validate;

class Teacher extends Validate
{
    protected $rule = [
        'name' => 'require|max:128',
        'order_key' => 'require|integer',
        'email' => 'require|email',
        'address'  => 'max:200'
    ];
    protected $message = [
        'name.require'              => '{%name_r}',
        'name.max'                  => '{%name_m}', 
        'order_key.require'         => '{%order_key_r}',
        'order_key.integer'         => '{%order_key_i}',
        'email'                     => '{%email_v}',
        'email.require'             => '{%email_r}',
        'address.max'               => '{%address_m}'
    ];
    protected $scene = [
        'add'   => ['name','order_key','email','address'],
        'edit'  => ['name','order_key','email','address'],
        'email' => ['email'],
        'address' => ['address'],
        'name' => ['name'],
        'state' => ['state'],
        'order_key' => ['order_key'],
    ];
}