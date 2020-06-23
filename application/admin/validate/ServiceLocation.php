<?php
namespace app\admin\validate;

use think\Validate;

class ServiceLocation extends Validate
{
    protected $rule = [
        'title'                 => 'require|max:128',
        'img'                   => 'max:255',
        'address'               => 'max:500',
        'tel'                   => 'max:128',
        'lng'                   => 'float',
        'lat'                   => 'float',
        'order_key'             => 'require|integer',
    ];
    protected $message = [
        'title.require'          => '{%title_r}',
        'title.max'              => '{%title_m}',
        'img.m'                  => '{img_m}',
        'address.max'            => '{%address_m}',
        'tel.max'                => '{%tel_m}',
        'lng.float'              => '{%lng_f}',
        'lat.float'              => '{%lat_f}', 
        'order_key.require'      => '{%order_key_r}',
        'order_key.integer'      => '{%order_key_i}',       
    ];
    protected $scene = [
        'add'       => ['title','img','address','tel','lng','lat','order_key'],
        'edit'      => ['title','img','address','tel','lng','lat','order_key'],
        'title'     => ['title'],
        'address'   => ['address'],
        'tel'       => ['tel'],
        'lng'       => ['lng'],
        'lat'       => ['lat'],
        'state'     => ['state'],
        'order_key' => ['order_key'],
    ];
}