<?php
use Gregwar\Captcha\CaptchaBuilder;

class IndexController extends Yaf_Controller_Abstract {

	/**
	 * IndexController::init()
	 * @return void
	 */
	public function init() {
		# parent::init();
        $user  = Yaf_Registry::get(SSN_VAR);
    }

	/**
	 * 显示整个后台页面框架及菜单
	 * @return string
	 */
	public function IndexAction() {
		$params = array();
		$params['user']  = Yaf_Registry::get(SSN_VAR);
//		$params['navtab']  =  $S->getPrivilegeListByPidUid($params['user']['sysno'],0);
        $params['navtab']  = array(
            array(
                'sysno'=>2,
                'privilegename'=>'商品管理'
            ),
            array(
                'sysno'=>1,
                'privilegename'=>'商户信息'
            ),
        );

		$this->getView()->make('index.index',$params);
	}

    /**
     * 显示登录页面
     */
	public function LoginAction() {
		$params = array();
		$this->getView()->make('index.login',$params);
	}

    /**
     * 显示退出登录页面
     */
	public function LogintimeoutAction() {
		$params = array();
		$this->getView()->make('index.logintimeout',$params);
	}

    /**
     * 用户登录请求
     */
	public function UserLoginAction()
	{
		$request = $this->getRequest();
		$params['username'] = $request->getpost('username','');
		$params['userpwd'] = $request->getpost('passwordhash','');

		$captcha = $request->getpost('captcha','');
		$session = Yaf_Session::getInstance();
		$phrase = $session->get('phrase');
		if($phrase != $captcha){
			$messgin = array();
			$messgin['msg'] = "验证码错误";
			$this->getView()->make('index.login',$messgin);
			return;
		}

		$S = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));

		if($user = $S->userLogin($params))
		{
			$ip = COMMON::getclientIp();
            setcookie ( "u_id", $user['sysno'], 0, "/", '.' . WEB_DOMAIN );
			Yaf_Session::getInstance ()->set ( SSN_VAR, $user );
//            $userUpdate = array('lastlogintime'=>'=NOW()','lastloginip'=>$ip);
//			if($S->setUserInfo($userUpdate,$user['sysno']))
//			{
//				unset($user['userpwd']);
//				setcookie ( "u_id", $user['sysno'], 0, "/", '.' . WEB_DOMAIN );
//				Yaf_Session::getInstance ()->set ( SSN_VAR, $user );
//			}
			header("Location: /" );
		}else{
			$messgin = array();
			$messgin['msg'] = "用户名或密码错误";
			$this->getView()->make('index.login',$messgin);
		}

	}

    /**
     * 用户ajax登录
     */
	public function ajaxLoginAction()
	{
		$request = $this->getRequest();
		$params['username'] = $request->getpost('username','');
		$params['userpwd'] = $request->getpost('passwordhash','');
		$S = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
		if($user = $S->userLogin($params))
		{
			$ip = COMMON::getclientIp();
			$userUpdate = array('lastlogintime'=>'=NOW()','lastloginip'=>$ip);

			if($S->setUserInfo($userUpdate,$user['sysno']))
			{
				unset($user['userpwd']);
				setcookie ( "u_id", $user['sysno'], 0, "/", '.' . WEB_DOMAIN );
				Yaf_Session::getInstance ()->set ( SSN_VAR, $user );
			}
			COMMON::result(200,'登陆成功');
		}else{

			COMMON::result(300,'用户名密码错误');
		}

	}

    /**
     * 修改密码
     */
	public function changepasswordAction() {
        $user = Yaf_Registry::get(SSN_VAR);
		$id = $user['sysno'];
		if(!$id)
		{
			COMMON::result(300,'请重新登录');
			return;
		}
		$U = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
		$action = "/user/passwordEditJson/";
        $params = $U->getUserById($id);
        $params['userRoles'] = $U->getUserPrivilege($id);
        $params['id'] =  $id;
        $params['action'] =  $action;

		$this->getView()->make('index.changepassword',$params);
	}

    /**
     * 切换navtab菜单
     */
	public function  navtabAction(){
		$res = array();
		$request = $this->getRequest();
		$id = $request->getParam('id',0);

		if(!$id){
			 echo json_encode($res);
			 return;
		}
		if($id==1){
            $res = array(
                array(
                    'id'=>"memu1",
                    'url'=>"/user/merchantInfo",
                    "name"=>"商户资料"
                )
            );
        }elseif ($id==2){
            $res = array(
                array(
                    'id'=>"memu2",
                    'url'=>"/goods/list",
                    "name"=>"商品管理"
                )
            );
        }

		echo json_encode($res);

	}

    /**
     * 退出登录请求
     */
	public function logOutAction()
	{
		$arr = array ();
		Yaf_Session::getInstance ()->set ( SSN_VAR, $arr );
		header("Location: /login");
	}

    /**
     * 验证码请求
     */
	public function vcodeAction()
	{
		$builder = new CaptchaBuilder;
		$builder->build();
		$session = Yaf_Session::getInstance();
		$session->set('phrase',$builder->getPhrase());
		header('Content-type: image/jpeg');
		$builder->output();
	}

}
