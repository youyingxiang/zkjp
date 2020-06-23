<?php

return [
    'id'                    => 'ID',
    'city'                  => 'City',
    'address'               => 'Address',
    'continent'             => 'Continent',
    'tel'                   => 'Contact Information',
    'mapx'                  => 'Map X Coordinates',
    'mapy'                  => 'Map Y Coordinates',
    'state'                 => 'Status',
    'order_key'             => 'Sort',
    'create_time'           => 'Creation Time',
    
    //数据验证提示 Data validation hints
    'city_r'                => 'City name must not be empty',
    'city_m'                => 'City name should not exceed 128 characters',
    'address_r'             => 'Address must not be empty',
    'address_m'             => 'Address should not exceed 500 characters',
    'mapx_r'                => 'Map X Coordinates must not be empty',
    'mapx_i'                => 'Map X Coordinates must be integer',
    'mapy_r'                => 'Map Y Coordinates must not be empty',
    'mapy_i'                => 'Map Y Coordinates must be integer',
    'tel_m'                 => 'Contact information should not exceed 255 characters',
    'order_key_r'           => 'Sorting must not be empty',
    'order_key_i'           => 'Sorting must be an integer',


    //其他 Others
    'show_title_sale_map'       => 'Contact Us',
    'show_title_add_sale_map'   => 'Add sales map information',
    'show_title_edit_sale_map'  => 'Edit sales map information',
    'show_title_lst_sale_map'   => 'Sales map information list',
    //日志 Journal
    'add_success_sale_map'      => 'Succeeded in adding sales map information',
    'add_error_sale_map'        => 'Failed to add sales map information',
    'edit_success_sale_map'     => 'Succeeded in editing sales map information',
    'edit_error_sale_map'       => 'Failed to edit sales map information',
    'del_success_sale_map'      => 'Succeeded in deleting sales map information',
    'del_error_sale_map'        => 'Failed to delete sales map information',

];
