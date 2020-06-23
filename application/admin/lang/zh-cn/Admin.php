<?php

return [
    'id'                    => 'ID',
    'admin_name'            => 'Name',
    'password'              => 'Password',
    'repassword'            => 'Confirm Password',
    'role_id'               => 'Role',
    'account'               => 'Account',
    'sex'                   => 'Gender',
    'state'                 => 'Status',
    'add_time'              => 'Create Time',
    'last_time'             => 'Last Login Time',
    'img'                   => 'Image (108*108)',
    
    //数据验证提示 Data validation hints
    'admin_name_require'    => 'User name must not be empty',
    'admin_name_min'        => 'User name should be at least one character',
    'account_require'       => 'Account must not be empty',
    'account_unique'        => 'Account already exists',
    'account_min'           => 'Account should be at least six characters',
    'password_val'          => 'Password must not be empty',
    'password_min'          => 'Password must not be less than 6 figures',
    'repassword_val'        => 'Please confirm the password',
    'repassword_confirm'    => 'Confirmed password and new password do not match',
    'sex_val'               => 'Sex must be a digital integer (0,1)',
    'status_val'            => 'Status must be a digital integer (0,1)',

    //其他 Others
    'top_pid'               => 'Top Permission',
    'show_title'            => 'Permission Management',
    'show_title_add_admin'  => 'Add Administrator',
    'show_title_edit_admin' => 'Modify Administrator',
    'show_title_lst_admin'  => 'Administrator List',
    //日志Journal
    'add_success_admin'     => 'Succeeded in adding administrator',
    'add_error_admin'       => 'Failed to add administrator',
    'edit_success_admin'    => 'Succeeded in modifying administrator',
    'edit_error_admin'      => 'Failed to modify administrator',

    'del_success_admin'     => 'Succeeded in deleting administrator',
    'del_error_admin'       => 'Failed to delete administrator',


];