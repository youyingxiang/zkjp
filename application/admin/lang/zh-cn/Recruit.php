<?php
return [
    'id'                    => 'ID',
    'name'                  => 'Position Name',
    'address'               => 'Working Address',
    'type'                  => 'Recruitment Type',
    'job_id'                => 'Post Category',
    'num'                   => 'The number of recruitment',
    'work_nature'           => 'Nature of Work',
    'content'               => 'Recruitment Content',
    'url'                   => 'Recruitment Link',
    'emergency'             => 'Urgent Recruitment',
    'state'                 => 'Status',
    'order_key'             => 'Sort',
    'create_time'           => 'Creation Time',
    'flag_1'                => 'Campus Recruitment',
    'flag_2'                => 'Social Recruitment',  
    
    //数据验证提示 Data validation hints
    'name_r'                => 'Position name must not be empty',
    'name_m'                => 'Position name should not exceed 128',
    'order_key_r'           => 'Sorting must not be empty',
    'order_key_i'           => 'Sorting must be an integer',
    'state_val'             => 'State must be an integer(0,1)',
    'num_r'                 => 'The number of recruitment must not be empty',
    'num_i'                 => 'The number of recruitment must be an integer',
    'url_m'                 => 'Recruitment link should not exceed 255 characters',

    //其他 Others
    'select_p'              => 'Choose Province',
    'select_c'              => 'Choose City',
    'show_title_recruit'    => 'Company Recruitment',
    'show_title_add_recruit'=> 'Add Recruitment',
    'show_title_edit_recruit' => 'Edit Recruitment',
    'show_title_lst_recruit'=> 'Recruitment List',
    //日志 Journal
    'add_success_recruit'   => 'Succeeded in adding recruitment',
    'add_error_recruit'     => 'Failed to add recruitment',
    'edit_success_recruit'  => 'Succeeded in editing recruitment',
    'edit_error_recruit'    => 'Failed to edit recruitment',
    'del_success_recruit'   => 'Succeeded in deleting recruitment',
    'del_error_recruit'     => 'Failed to delete recruitment',

];
