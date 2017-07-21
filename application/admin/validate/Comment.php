<?php
namespace app\admin\validate;
use think\Validate;
use think\Db;
class Comment extends Validate
{
    protected $rule=[
    'username'  => 'require|max:25|unique:admin',//|unique:admin
    'password' => 'require',
    ];

    protected $message  =   [
        'username.require' => '管理员名称必须填写',
        'username.max'     => '管理员名称长度不得大于25位',
        'password.require' => '管理员密码必须填写',
        'username.unique' => '管理员名称不得重复',
        ];



    //验证场景

    //只验证username或者用户名密码都验证
    //应用的话需要在controller的admin文件里调用
    protected $scene = [
        //虽然上边定义了很多验证规则，但是我这里只验证必填
//        'add'  =>  ['username'=>'require|min:25','password'],
        'add'  =>  ['username'=>'require|unique:admin','password'],
        //编辑模式下使密码不用验证
        'edit' =>  ['username'=>'require|unique:admin'],
//        'add'  =>  ['username'],
    ];
}