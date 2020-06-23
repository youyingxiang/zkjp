<?php

return [
    'id'                    => 'ID',
    'job_name'              => 'Job Position',
    'describe'              => 'Position Description',
    'state'                 => 'Status',
    'order_key'             => 'Sort',
    'create_time'           => 'Creation Time',
    
    //数据验证提示 Data validation hints
    'job_name_r'            => 'Job position must not be empty',
    'job_name_m'            => 'Job position should not exceed 128 characters',
    'state_val'             => 'State must be an integer',
    'order_key_r'           => 'Sorting must not be empty',
    'order_key_i'           => 'Sorting must be an integer',

    //其他 Others
    'top_pid'               => 'Top Permission',
    'show_title_post'       => 'Company Recruitment',
    'show_title_add_post'   => 'Add Post',
    'show_title_edit_post'  => 'Edit Post',
    'show_title_lst_post'   => 'Post List',
    //日志 Journal
    'add_success_post'      => 'Succeeded in adding post',
    'add_error_post'        => 'Failed to add post',
    'edit_success_post'     => 'Succeeded in editing post',
    'edit_error_post'       => 'Failed to edit post',
    'del_success_post'      => 'Succeeded in deleting post',
    'del_error_post'        => 'Failed to delete post',

];
