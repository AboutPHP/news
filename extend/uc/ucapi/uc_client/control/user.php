<?php
namespace  app\user\Controller;

use think\Controller;
/**
 * 
 * @author yaolishuang
 *
 */
class User extends Controller{
	/**
	 * 注册
	 * @return \think\response\View
	 */
	public function register(){
		if(request()->isPost()&&input("post.")){
			$foo = new \uc\ucapi\uc();
			/**校验以下邮箱是否已经存在**/
			echo $_POST['username'];
			$uid = uc_user_register($_POST['username'], $_POST['password'], $_POST['ip']);
			if($uid <= 0) {
				if($uid == -1) {
					echo '用户名不合法';
				} elseif($uid == -2) {
					echo '包含要允许注册的词语';
				} elseif($uid == -3) {
					echo '用户名已经存在';
				} elseif($uid == -4) {
					echo 'Email 格式有误';
				} elseif($uid == -5) {
					echo 'Email 不允许注册';
				} elseif($uid == -6) {
					echo '该 Email 已经被注册';
				} else {
					echo '未定义';
				}
			} else {
				//注册成功，设置 Cookie，加密直接用 uc_authcode 函数，用户使用自己的函数
				setcookie('Example_auth', uc_authcode($uid."\t".$_POST['username'], 'ENCODE'));
				echo '注册成功<br><a href="'.$_SERVER['PHP_SELF'].'">继续</a>';
			}
		}
		return view();
	}
	/**
	 * 登陆
	 * @return \think\response\View
	 */
	public function login(){
		if(request()->isPost()){
			$foo = new \uc\ucapi\uc();
			if($this->checkCapache($_POST['code'])){
				list($uid, $username, $password, $email,$guid) = uc_user_login($_POST['username'], $_POST['password']);
				if($uid > 0) {
					echo '登录成功';
					echo uc_user_synlogin($uid);//同步登陆
					$this->redirect('index', array('guid'=>$guid), 3, '页面跳转中~');
				} elseif($uid == -1) {
					echo '用户不存在,或者被删除';
				} elseif($uid == -2) {
					echo '密码错';
				} else {
					echo '未定义';
				}
			}else {
				$this->error("验证码错误");
			}
			
		}else{
			return view();
		}
		
	}
	public function index(){
		return view();
	}
	public function positionform(){
		new CommonUtil();
		$this->assign("educationType",CommonUtil::userEduType());
		return view("user/positonform");
	}
	/**
	 * 发送验证码
	 * @return string[]|number[]|unknown[]
	 */
	public function makeusercode(){
		$foo = new \uc\ucapi\uc();
		/**查看邮箱是否合法**/
		$loginflag = $_GET['loginflag'];
		/**先进行邮箱校验**/
		$result = array();
		/**先检验是否为邮箱**/
		if(uc_user_checkemailformat($loginflag)>0){
			/**查看邮箱是否合法**/
			$ucresult = uc_user_checkemail($_GET['loginflag']);
			$result = array();
			if($ucresult > 0) {
				$code = rand("100000","999999");
				/**发送邮件**/
				$bool = uc_mail_queue('', $_GET['loginflag'],"验证码",$code,'utf-8','',0);
				if(empty($bool)) {
					$result['message'] = '加入邮件队列失败';
				} else {
					$result['message'] = '加入邮件队列成功';
					session_start();
					$_SESSION['userCheckCode']=$code;
					/**将验证码存储到session中**/
				}
			} elseif($ucresult == -4) {
				$result['message'] = 'Email 格式有误';
			} elseif($ucresult == -5) {
				$result['message'] = 'Email 不允许注册';
			} elseif($ucresult == -6) {
				$result['message'] = '该 Email 已经被注册';
			}
			
		}elseif(uc_user_checkmobileformat($loginflag)){  /**进行手机号校验**/	
			//检测手机号格式是否正确
			$code = rand("100000","999999");
			$ucresult = uc_user_sms($loginflag,$code);//发送短信
			if($ucresult == -1){
				$result['message'] = '手机校验码发送失败';
			}else if($ucresult>0){
				$result['message'] = '手机校验码已发送';
				/**将校验码存入到cookie**/
				session_start();
				$_SESSION['userCheckCode']=$code;
			}elseif($ucresult == -4){
				$result['message']='手机号输入有误';
			}elseif($ucresult == -3){
				$result['message'] = '手机号已经被注册';
			}else{
				$result['message'] = '其他错误';
			}
		}else{
			$result['message'] = '输入有误，请重新输入';
		}
		$result['statusId'] = $ucresult;
		return $result;
	}
	/**
	 * 发送验证码
	 * @return string[]|number[]|unknown[]
	 */
	public function userEmail(){
		$foo = new \uc\ucapi\uc();
		/**查看邮箱是否合法**/
		echo $_GET['loginflag'];
		$ucresult = uc_user_checkemail($_GET['loginflag']);
		$result = array();
		if($ucresult > 0) {
			$code = rand("100000","999999");
			/**发送邮件**/
			$bool = uc_mail_queue('', $_GET['loginflag'],"验证码",$code,'utf-8','',0);
			if(empty($bool)) {
				$result['message'] = '加入邮件队列失败';
			} else {
				$result['message'] = '加入邮件队列成功';
				$result['code'] = $code;
				/**将验证码存储到session中**/
			}
		} elseif($ucresult == -4) {
			$result['message'] = 'Email 格式有误';
		} elseif($ucresult == -5) {
			$result['message'] = 'Email 不允许注册';
		} elseif($ucresult == -6) {
			$result['message'] = '该 Email 已经被注册';
		}
		$result['statusId'] = $ucresult;
		return $result;
	}
	/**
	 * 图片验证码校验
	 * @param unknown $capache
	 * @return boolean
	 */
	public function checkCapache($capache){
		if(captcha_check($capache)){
			return true;
		}
		return false;
	}
	/**
	 * 校验手机或者邮箱验证码是否合法
	 */
	public function checkUserCode(){
		session_start();/**开启session获取邮箱或者手机号验证码**/
		$result = array();
		if($_GET['userCheckCode']==$_SESSION['userCheckCode']){
			$result['status']=true;
		}else{
			$result['status']=false;
			$result['message']="校验码输入错误";
		}
		return $result;
	}

    /**
     * 用户修改登录密码
     * @return \think\response\View
     */
    function upPassword()
    {
        $foo = new \uc\ucapi\uc();
        if (request()->isPost() && input('post.')) {
            //获取用户信息
            $username = input('username');
            $password = input('password');
            if ($data = uc_get_user($username)) {
                list($uid, $username, $email) = $data;
                //修改密码
                $this->upUser($username,$password);
            } else {
                echo '用户不存在';
            }
        } else {
            return view();
        }

    }
    /**
     * 更新数据库用户资料
     * @param $username
     * @param $password
     */
    function upUser($username,$password){
        $ucresult = uc_user_edit($username, '', $password, '',1,'','');
        if($ucresult == -1) {
            echo '旧密码不正确';
        } elseif($ucresult == -4) {
            echo 'Email 格式有误';
        } elseif($ucresult == -5) {
            echo 'Email 不允许注册';
        } elseif($ucresult == -6) {
            echo '该 Email 已经被注册';
        }else{
            echo '修改成功';
        }
    }
}