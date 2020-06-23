<?php
namespace app\admin\validate;

use think\Validate;

class SaleMap extends Validate
{
    protected $rule = [
        'city' => 'require|max:128',
        'address' => 'require|max:500',
        'mapx' => 'require|integer',
        'mapy' => 'require|integer',
        'tel' => 'max:255',
        'order_key' => 'require|integer',
    ];
    protected $message = [
        'city.require'             => '{%city_r}',
        'city.max'                 => '{%city_m}',
        'tel.max'                  => '{%tel_m}',
        'address.require'          => '{%address_r}',
        'address.max'              => '{%address_m}',
        'mapx.require'             => '{%mapx_r}',
        'mapx.integer'             => '{%mapx_i}',
        'mapy.require'             => '{%mapy_r}',
        'mapy.integer'             => '{%mapy_i}',
        'order_key.require'        => '{%order_key_r}',
        'order_key.integer'        => '{%order_key_i}',
    ];
    protected $scene = [
        'add'   => ['city','address','mapx','mapy','order_key','tel'],
        'edit'  => ['city','address','mapx','mapy','order_key','tel'],
        'city' => ['city'],
        'address'  => ['address'],
        'mapy'   => ['mapy'],
        'mapx'   => ['mapx'],
        'tel'    => ['tel'],
        'state' => ['state'],
        'order_key' => ['order_key'],
    ];
}