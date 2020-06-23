<?php
namespace app\admin\validate;

use think\Validate;

class Contactus extends Validate
{
    protected $rule = [
        'name' => 'require|max:128',
        'address' => 'require|max:500',
        'company_name' => 'require|max:255',
        'email' => 'email',
        'tel' => 'max:128',
    ];
    protected $message = [
        'name.require'             => '{%name_r}',
        'name.max'                 => '{%name_m}',
        'address.require'          => '{%address_r}',
        'address.max'              => '{%address_m}',
        'email'                    => '{%email_v}',
        'tel.max'                  => '{%tel_m}',
        'company_name.require'     => '{%company_name_r}',
        'company_name.max'         => '{%company_name_m}',
    ];
    protected $scene = [
        'add'   => ['name','address','company_name','email','tel'],
        'edit'  => ['name','address','company_name','email','tel'],
        'name' => ['name'],
        'address'  => ['address'],
        'company_name'  => ['company_name'],
        'tel'    => ['tel'],
        'state' => ['state'],
        'order_key' => ['order_key'],
    ];
}