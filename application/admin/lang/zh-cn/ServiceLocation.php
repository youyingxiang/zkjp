<?php

return [
    'id'                    => 'ID',
    'title'                 => 'Title',
    'procity'               => 'Province and city',
    'province'              => 'Province',
    'city'                  => 'City',
    'address'               => 'Detailed Address',
    'tel'                   => 'Telephone Number',
    'img'                   => 'Picture(93*76)',
    'lng'                   => 'Longitude',
    'lat'                   => 'Latitude',
    'state'                 => 'State',
    'order_key'             => 'Sort',
    'create_time'           => 'Creation Time',
    
    //数据验证提示 Data validation hints
    'title_r'               => 'Title must not be empty',
    'title_m'               => 'Title should not exceed 128 characters',
    'tel_m'                 => 'Telephone number should not exceed 128 characters',
    'address_m'             => 'Address should not exceed 500 characters',
    'img_m'                 => 'Picture should not exceed 255 characters',                  
    'lng_f'                 => 'The format of longitude is incorrect',
    'lat_f'                 => 'The format of latitude is incorrect',         
    'order_key_r'           => 'Sorting must not be empty',
    'order_key_i'           => 'Sorting must be an integer',

    //其他 Others
    'show_title_svrlcn'       => 'Contact Us',
    'show_title_add_svrlcn'   => 'Add after-sale service point',
    'show_title_edit_svrlcn'  => 'Edit after-sale service point',
    'show_title_lst_svrlcn'   => 'After-sale service point list',
    //日志 Journal
    'add_success_svrlcn'      => 'Succeeded in adding after-sale service point',
    'add_error_svrlcn'        => 'Failed to add after-sale service point',
    'edit_success_svrlcn'     => 'Succeeded in editing after-sale service point',
    'edit_error_svrlcn'       => 'Failed to edit after-sale service point',
    'del_success_svrlcn'      => 'Succeeded in deleting after-sale service point',
    'del_error_svrlcn'        => 'Failed to delete after-sale service point',

];
