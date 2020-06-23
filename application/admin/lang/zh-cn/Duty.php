<?php
return [
    'id'                    => 'ID',
    'typeid'                => 'Classification',
    'title'                 => 'Title',
    'img'                   => 'Picture(700*300)',
    'banner'                => 'banner(1920*300)',
    'seo_keyword'           => 'Keywords',
    'seo_des'               => 'Description',
    'abstract'              => 'Abstract',
    'content'               => 'Content',
    'state'                 => 'Status',
    'create_time'           => 'Creation Time',
    'update_time'           => 'Editing Time',
    
    //数据验证提示Data validation hints
    'title_r'               => 'Title must not be empty',
    'title_m'               => 'Title should not exceed 200 characters',
    'img_m'                 => 'Picture address should not exceed 255 characters',
    'seo_keyword_m'         => 'Seo keywords should not exceed 200 characters',
    'seo_des_m'             => 'Seo description should not exceed 500 characters',


    //其他
    'show_title_duty'       => 'About Us',
    'show_title_add_duty'   => 'Add social responsibility',
    'show_title_edit_duty'  => 'Edit social responsibility',
    'show_title_lst_duty'   => 'Social responsibility list',

    //日志 Journal
    'add_success_duty'      => 'Succeeded in adding social responsibility',
    'add_error_duty'        => 'Failed to add social responsibility',
    'edit_success_duty'     => 'Succeeded in editing social responsibility',
    'edit_error_duty'       => 'Failed to edit social responsibility',
    'del_success_duty'      => 'Succeeded in deleting social responsibility',
    'del_error_duty'        => 'Failed to delete social responsibility',
];