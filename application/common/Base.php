<?php
/**
 * Created by PhpStorm.
 * User: sunzhaoquan
 * Date: 2017/7/14 0014
 * TIME:15:10
 */
namespace app\common;
use think\Controller;
use think\Session;
use think\Request;
class Base extends Common{
    function __construct(Request $request)
    {
        //构造方法中先加载父类的构造方法，防止父类的构造方法被覆盖。
        parent::__construct();
        //接受session值
        $user_name = Session::get('username');
        //定义当前访问的控制器-方法名
        $now_auth =strtolower($request->module()).'/'.strtolower($request->controller()).'/'.strtolower($request->action());
        //定义允许访问的权限
        $allow_auth = "user/manager/login,user/manager/logout,user/manager/register,index/index/index,index/news/index,index/index/art_show,index/index/commend,index/news/detail";
//        if(strpos($allow_auth,$now_auth)===false && $user_name == false){
//            exit("
//                <script type='text/javascript'>
//                window.location.href='/user/manager/login';
//    </script>
//            ");
//        }
        $this->assign('username',$user_name);
    }
}