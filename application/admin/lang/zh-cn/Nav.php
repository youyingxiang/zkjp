<?php

return [
    'id'                    => 'ID',
    'name'                  => 'Navigation Name',
    'parent_id'             => 'Upper Navigation',
    'url'                   => 'URL which the navigational will jump to',
    'show_position'         => 'Navigation Show',
    'order_key'             => 'Sort',
    
    //数据验证提示Data validation hints
    'parent_id_i'           => 'Navigation must be an integer',
    'parent_id_r'           => 'Navigation must not be empty',
    'name_r'                => 'Navigation name must not be empty',
    'name_u'                => 'Navigation name has already existed',
    'name_m'                => 'Navigation name should not exceed 128 characters',
    'order_key_r'           => 'Sorting must not be empty',
    'order_key_i'           => 'Sorting must be an integer',

    //其他 Others
    'top_pid'               => 'Top Navigation',
    'show_title_config'     => 'System Management',
    'show_title_add_nav'    => 'Add Navigation',
    'show_title_edit_nav'   => 'Edit Navigation',
    'show_title_lst_nav'    => 'Navigation List',
    //日志 Journal
    'add_success_nav'     => 'Succeeded in adding Navigation',
    'add_error_nav'       => 'Failed to add Navigation',
    'edit_success_nav'    => 'Succeeded in modifying Navigation',
    'edit_error_nav'      => 'Failed to modify Navigation',
    'del_success_nav'     => 'Succeeded in deleting Navigation',
    'del_error_nav'       => 'Failed to delete Navigation',


    'show_position_1'     => 'Show at the top',
    'show_position_2'     => 'Show at the bottom',
    'show_position_3'     => 'Show at both positions',
    'show_position_4'     => 'Do not show',


];
