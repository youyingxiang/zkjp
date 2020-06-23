<?php

return [
    'id'                    => 'ID',
    'title'                 => 'Title',
    'desc'                  => 'Company Name',
    'url'                   => 'Link Address',
    'state'                 => 'Status',
    'order_key'             => 'Sort',
    'create_time'           => 'Creation Time',
    
    //数据验证提示Data validation hints
    'title_r'               => 'Title must not be empty',
    'title_m'               => 'Title should not exceed 128 characters',
    'desc_r'                => 'Company name must not be empty',
    'desc_m'                => 'Company name should not exceed 500 characters',
    'url_m'                 => 'Link address should not exceed 500 characters',          
    'order_key_r'           => 'Sorting must not be empty',
    'order_key_i'           => 'Sorting must be an integer',

    //其他 Others
    'show_title_onlinestore'       => 'How to buy',
    'show_title_add_onlinestore'   => 'Add online store',
    'show_title_edit_onlinestore'  => 'Edit online store',
    'show_title_lst_onlinestore'   => 'Online store list',
    //日志 Journal
    'add_success_onlinestore'      => 'Succeeded in adding online store',
    'add_error_onlinestore'        => 'Failed to add online store',
    'edit_success_onlinestore'     => 'Succeeded in editing online store',
    'edit_error_onlinestore'       => 'Failed to edit online store',
    'del_success_onlinestore'      => 'Succeeded in deleting online store',
    'del_error_onlinestore'        => 'Failed to delete online store',

];
