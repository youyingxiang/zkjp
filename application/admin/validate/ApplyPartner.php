<?php
namespace app\admin\validate;

use think\Validate;

class ApplyPartner extends Validate
{
    protected $rule = [
        'company_name'  => 'require|max:128',
        'name' => 'require|max:128',
        'phone' => 'require',
        'email' => 'require|email',
    ];
    protected $message = [
        'company_name.require' => '{%company_name_r}',
        'company_name.max' => '{%company_name_m}',
        'name.require' => '{%name_r}',
        'name.max' => '{%name_m}',
        'phone.require' => '{%phone_r}',
        'email' => '{%email_v}',
        'email.require' => '{%email_r}',    
    ];
    protected $scene = [
        'add'   => ['company_name', 'name','phone','email'],
        'edit'  => ['company_name', 'name','phone','email'],
        'company_name' => ['company_name'],
        'name' => ['name'],
        'phone' => ['phone'],
        'email' => ['email'],
        'job' => ['job'],
        'city'  => ['city'],
        'address'  => ['address'],
    ];
}