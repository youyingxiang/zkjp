<?php
namespace app\admin\validate;

use think\Validate;

class Course extends Validate
{
    protected $rule = [
        'name' => 'require|max:128',
        'order_key' => 'require|integer',
        'img'  => 'max:255',
        'time_long' => 'integer',
        'url' => 'max:255',
        'teatcher' => 'max:255',
        'time_detail' => 'max:255',
        'language'  => 'max:200',
        'type'  => 'max:128',
        'time1' => 'max:128',
        'time2' => 'max:128',
        'time3' => 'max:128',
        'time4' => 'max:128',
        'time5' => 'max:128',
        'time6' => 'max:128',
        'time7' => 'max:128',

    ];
    protected $message = [
        'name.require'              => '{%name_r}',
        'name.max'                  => '{%name_m}', 
        'order_key.require'         => '{%order_key_r}',
        'order_key.integer'         => '{%order_key_i}',
        'img.max'                   => '{%img_m}',
        'time_long.integer'         => '{%time_long_i}',
        'url.max'                   => '{%url_m}',
        'teatcher.max'              => '{%teatcher_m}',
        'time_detail.max'           => '{%time_detail_m}',
        'language.max'              => '{%time_m}',
        'type.max'                 => '{%time_m}',
        'time1.max'                 => '{%time_m}',
        'time2.max'                 => '{%time_m}',
        'time3.max'                 => '{%time_m}',
        'time4.max'                 => '{%time_m}',
        'time5.max'                 => '{%time_m}',
        'time6.max'                 => '{%time_m}',
        'time7.max'                 => '{%time_m}',


    ];
    protected $scene = [
        'add'   => ['language','name','order_key','time_long','time1','time2','time3','time4','time5','time6','time7','img','teatcher','time_detail','type','url'],
        'edit'  => ['language','name','order_key','time_long','time1','time2','time3','time4','time5','time6','time7','img','teatcher','time_detail','type','url'],
        'name' => ['name'],
        'teatcher' => ['teatcher'],
        'language' => ['language'],
        'time_detail' => ['time_detail'],
        'time_long' => ['time_long'],
        'type' => ['type'],
        'state' => ['state'],
        'order_key' => ['order_key'],
    ];
}