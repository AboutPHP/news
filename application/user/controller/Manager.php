<?php
/**
 * Created by PhpStorm.
 * User: sunzhaoquan
 * Date: 2017/7/5 0005
 * TIME:16:15
 */
namespace app\user\controller;
use app\admin\model\ArticleModel;
use app\admin\model\Cate as CateModel;
use app\common\Base;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\File;
class Manager extends Base
{
    public function _initialize(){
        new \uc\ucapi\uc();//加载服务器函数
    }
    /**
     * [login 实现登录]
     * @by author sunzhaoquan
     */
    public function login(Request $request)
    {
        $result = array();
        if ($request->isPost()) {
            $result = uc_user_login($request->post('username'), $request->post('password'));
            if (is_null($result)) {
                $result['status'] == 300;
                $result['message'] = "服务端程序错误";
            } elseif (empty($result)) {
                $result['status'] == 300;
                $result['message'] = "未通信成功";
            } elseif ($result['status'] == 200) {
                session('Example_auth', uc_authcode($result['data']['guid'] . "\t" . $result['data']['username'], 'ENCODE'));
                unset($result['data']);
                if ($result['status'] == 200) {
//                    //持久化session信息
                    Session::set('username', $request->post('username'));
                    Session::set('expire', 1800);//设置session的过期时间为1800秒
                    $this->redirect(url('index/index'), $result['status'],200);
                }
            } else {
                $this->assign('error_info', $result);
                return view('manager/login');//跳转到登陆页面
            }
        } else {
            return view('manager/login');//跳转到登陆页面
        }
    }
    /**
     * 注册
     * @return \think\response\View
     */
    public function register(Request $request){
        if(request()->isPost()){
            $username = $request->post('phone');
            $password = $request->post('pwd');
            $result = array();
            if($request->post('code') == Session::get('userCheckCode')){
                $result = uc_user_register($username, $password,$ip=null);
                if($result['status']==200){
                    session('Example_auth', uc_authcode($result['data']['guid']."\t".$result['data']['username'], 'ENCODE'));
                }
            }else{
                $result['status']=300;
                $result['message']="校验码输入错误";
            }
            return $result;
        }
        return view('manager/register');
    }

    /**
     * 生成验证码
     * @author:yaolsh
     * @param Request $request
     * 代码优化，将业务相关的都转移到了服务端。客户端只负责接收执行成功还是失败
     */
    public function makeCode(Request $request){
        $regflag = $request->get('username');
        $result = uc_user_usercode($regflag);
        if($result['status']==200){
            Session::set('userCheckCode',$result['data']['code']);
            return $result;
        }else{
            return $result;//将失败的信息返回
        }
    }
    /**
     * 校验手机或者邮箱验证码是否合法
     */
    public function checkUserCode(Request $request){
        $result = array();
        if($request->get('userCheckCode')==Session::get('userCheckCode')){
            uc_user_register($request->post('phone'), $request->post('pwd'), null);
        }else{
            $result['status']=false;
            $result['message']="校验码输入错误";
        }
        return $result;
    }
    public function test(){
        $this->assign('data', uc_get_cityinfo());
        return view('user/address_chose');
    }
}