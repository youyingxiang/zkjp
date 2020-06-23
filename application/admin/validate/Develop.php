<?php
namespace app\admin\validate;

use think\Validate;

class Develop extends Validate
{
    protected $rule = [
        'title' => 'require|unique:file|max:128',
    ];
    protected $message = [
        'title.require'             => '{%title_r}',
        'title.unique'              => '{%title_u}',
    ];
    protected $scene = [
        'add'   => ['title'],
        'edit'  => ['title'],
        'title' => ['title'],
        'state' => ['state']
    ];
}