<?php
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'admin_name' => 'require|min:1',
        'account' => 'require|min:6|unique:admin',
        'password' => 'require|min:6',
        'repassword' => 'require|confirm:password',
        'role_id' =>   'require|integer',
        'sex' => 'require|in:0,1',
        'state' => 'require|in:0,1',
    ];

    protected $message = [
        'admin_name.require' => '{%admin_name_require}',
        'admin_name.min' => '{%admin_namee_min}',
        'account.require' => '{%account_require}',
        'account.unique' => '{%account_unique}',      
        'account.min' => '{%account_min}',
        'password.require' => '{%password_val}',
        'password.min' => '{%password_min}',
        'repassword.require' => '{%repassword_val}',
        'repassword.confirm' => '{%repassword_confirm}',
        'sex' => '{%sex_val}',
        'state' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['admin_name', 'account', 'password', 'repassword', 'role_id', 'sex', 'state'],
        'edit'  => ['admin_name', 'account', 'password', 'repassword', 'role_id', 'sex', 'state'],
        'password' => ['password', 'repassword'],
        'state' => ['state'],
        'admin_name' => ['admin_name'],
        'account' => ['account'],
        'sex' => ['sex'],
    ];
}