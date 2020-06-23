<?php

return [
    'id'                    => 'ID',
    'name'                  => 'Language Name',
    'url'                   => 'Jump Link',
    'state'                 => 'Status',
    'order_key'             => 'Sort',
    'create_time'           => 'Creation Time',
    
    //数据验证提示Data validation hints
    'name_r'                => 'Language name must not be empty',
    'name_m'                => 'Language name should not exceed 128 characters',
    'url_m'                 => 'Link should not exceed 255 characters',
    'state_val'             => 'State must be an integer (0,1)',
    'order_key_r'           => 'Sorting must not be empty',
    'order_key_i'           => 'Sorting must be an integer',

    //其他 Others
    'top_pid'                      => 'Top Permission',
    'show_title_LanguageWeb'       => 'System Management',
    'show_title_add_LanguageWeb'   => 'Add regional language',
    'show_title_edit_LanguageWeb'  => 'Edit regional language',
    'show_title_lst_LanguageWeb'   => 'Regional language ',
    
//日志 Journal
    'add_success_LanguageWeb'      => 'Succeeded in adding regional language',
    'add_error_LanguageWeb'        => 'Failed to add regional language',
    'edit_success_LanguageWeb'     => 'Succeeded in editing regional language',
    'edit_error_LanguageWeb'       => 'Failed to edit regional language',
    'del_success_LanguageWeb'      => 'Succeeded in deleting regional language',
    'del_error_LanguageWeb'        => 'Failed to delete regional language',

];
