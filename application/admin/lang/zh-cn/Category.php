<?php

return [
    'id'                    => 'ID',
    'name'                  => 'Classification Name',
    'state'                 => 'Status',
    'parent_id'             => 'Superior Classification',
    'img'                   => 'Classified Pictures',
    'img_p'                 => 'Classified Pictures (17*18)',
    'img_n'                 => 'Classified Pictures (54*60)',
    'down_auth'             => 'Users who are authorized to download',         
    'page'                  => 'Paging Quantity',
    'seo_keyword'           => 'SEO keywords',
    'seo_des'               => 'SEO description',
    'order_key'             => 'Sort',
    
    //数据验证提示 Data validation hints
    'parent_id_i'           => 'Parent ID must be an integer',
    'parent_id_r'           => 'Parent ID must not be empty',
    'name_r'                => 'Classification name must not be empty',
    'name_u'                => 'Classification name has already existed',
    'name_m'                => 'Classification name should not exceed 128 characters',
    'order_key_r'           => 'Sorting must not be empty',
    'order_key_i'           => 'Sorting must be an integer',
    'page_r'                => 'Paging quantity must not be empty',
    'page_i'                => 'Paging quantity must be an integer',

    'category_msg'          => 'News center in navigation list should show the top 4 categories',


    //其他 Others
    'top_pid'               => 'Top Classification',
    'show_title_news'       => 'News Management',
    'show_title_product'    => 'Product Management',
    'show_title_file'       => 'Service Support',
    'show_title_add_cat'    => 'Add Classification',
    'show_title_edit_cat'   => 'Modify Classification',
    'show_title_lst_cat'    => 'Classification List',


    //日志 Journal
    'add_success_cat'     => 'Succeeded in adding classification',
    'add_error_cat'       => 'Failed to add classification',
    'edit_success_cat'    => 'Succeed in modifying classification',
    'edit_error_cat'      => 'Failed to modify classification',
    'del_success_cat'     => 'Succeeded in deleting classification',
    'del_error_cat'       => 'Failed to delete classification',




];
