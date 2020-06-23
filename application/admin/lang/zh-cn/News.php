<?php
return [
    'id'                    => 'ID',
    'typeid'                => 'Classification',
    'title'                 => 'Title',
    'flag'                  => 'Attribute',
    'jumplink'              => 'Jump Link',
    'img'                   => 'Picture',
    'author'                => 'Author',
    'source'                => 'Source',
    'seo_keyword'           => 'Keywords',
    'seo_des'               => 'Description',
    'abstract'              => 'Abstract',
    'content'               => 'Content',
    'click'                 => 'Hits',
    'state'                 => 'States',
    'create_time'           => 'Creation Time',

    'update_time'           => 'Releasing Time',
    'push'                  => 'Push',
    'link_url'              => 'Link',
    
    //数据验证提示Data validation hints
    'title_r'               => 'Title must not be empty',
    'title_m'               => 'Title should not exceed 200 characters',
    'jumplink_m'            => 'Jump link should not exceed 200 characters',
    'img_m'                 => 'Picture address should not exceed 255 characters',
    'author_m'              => 'Author name should not exceed 255 characters',
    'source_m'              => 'Resource address should not exceed 255 characters',
    'seo_keyword_m'         => 'SEO keywords should not exceed 200 characters',
    'seo_des_m'             => 'SEO description should not exceed 500 characters',
    'click_r'               => 'Hits must not be empty',
    'click_i'               => 'Hits must be an integer',
    'email_push_s'          => 'Succeeded in pushing to email',
    'email_push_e'          => 'Failed to push to email',

    'ordinary'              => 'Ordinary',
    //其他
    'top_pid'               => 'Top Permission',
    'show_title_news'       => 'News Management',
    'show_title_add_news'   => 'Add News',
    'show_title_edit_news'  => 'Edit News',
    'show_title_lst_news'   => 'News List',

    //日志Journal
    'add_success_news'      => 'Succeeded in adding news',
    'add_error_news'        => 'Failed to add news',
    'edit_success_news'     => 'Succeeded in editing news',
    'edit_error_news'       => 'Failed to edit news',
    'del_success_news'      => 'Succeeded in deleting news',
    'del_error_news'        => 'Failed to delete news',

];
