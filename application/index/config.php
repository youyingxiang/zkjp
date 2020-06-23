<?php
/*
 * 后台模块配置文件
 */
return [
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'html',
    // 是否开启多语言
    'lang_switch_on'         => true,
    // 默认语言
    'default_lang'           => 'en-us', 

    'lang_list'              => ['zh-cn','en-us'],
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
  	//分页配置
    'paginate'               => [
                 'type'      => '\\expand\\Zkpage',
                 'var_page'  => 'page',
                 'list_rows' => 8,
                ],
    'projectScale' => [
            1 => 'Large projects',
            2 => 'Medium projects',
            3 => 'Small projects',
    ],
    'express' => [
            1 => '顺丰',
            2 => '圆通',
            3 => '申通',
            4 => '中通',
            5 => '邮政快递',
            6 => '韵达',
            7 => '天天快递 ',
            8 => '德邦物流',
            9 => '其他',
    ],
    'status_repair_detail' => [
        0 => 'Send in Repairing',
        1 => 'In Maintenance',
        2 => 'Repaired',
        3 => 'No Accessories',
    ],
    'status_repair_lst' => [
        0 => 'Send in Repairing',
        2 => 'In Maintenance',
        3 => 'Maintenance Finished',
        6 => 'Maintenance Terminated',
    ],
    'status_ticket_lst' => [
        '' => 'Please Select',
        'UnProcessed' => 'Unprocessed',
        'Processing' => 'Processing',
        'Finished' => 'Finished',
        'Evaluated' => 'Evaluated',
    ],
    'status_reservstion' => [
        0 => 'Pending',
        1 => 'Reserve Successfully',
        2 => 'Door-to-door Service Finished',
        3 => 'Commented Already',
    ], 
/*    'customize_state' => [
        'Processing',
        'Finish',
        'Suspend',
    ],*/
    'customize_state' => [
        'Estimating',
        'Charge',
        'Ongoing',
        'Finished'
    ],
    'auth' => [
        'user_center',
        'feedback',
        'authentication',
        'apply_pwdback',
        'apply_gold_vip',
        'user_info',
        'update_password',
        'repair_register',
        'repair_register_step2',
        'order_step1',
        'order_step2',
        'project_consultation',
        'reservation',
        'ticket_save',
        'ticket_query',
        'repair_query',
        'zk_ticket_detail',                                   //添加问题工单钩子拦截
        'zk_repair_detail',
        'reservation_query',
        'zk_reserv_detail',
        'download_history',
	'service_location',
        'subscribe',
/*        'service',*/
        'customized_query'
    ],
    'product_type' => [
        'Time Attendance',
        'Access Control',
        'Control Panel',
    ],
    'month_date' => [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ],
    'commentScore' => [
        4 => 'Very Good',
        3 => 'Good',
        2 => 'General',
        1 => 'Very Bad',
    ]
];
