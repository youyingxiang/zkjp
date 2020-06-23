<?php
namespace app\admin\controller;
use think\Controller;
use think\Lang;
use think\config;
use think\Session;
use app\admin\model\Admin as Admin_m;
class Login extends Controller
{
	private $cModel;   //当前控制器关联模型
    
    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Admin_m;   //别名：避免与控制名冲突
    }

	public function login()
    {
    	if (request()->isPost()) {
            $data = input('post.');
            if (!captcha_check($data['code'])) {
                return ajaxReturn(lang('code_error'));
            };
            $where = ['account' => $data['account'] ];
            $adminData = $this->cModel->where($where)->find();
            if (!empty($adminData)) {
                if ($adminData['state'] != '1') { 
                    return ajaxReturn(lang('user_stop'));
                } elseif ($adminData['password'] != md5($data['password'].config('md5_code'))) {
                    return ajaxReturn(lang('password_error'));                   
                } elseif (($adminData['password'] == md5($data['password'].config('md5_code')))) {
                    $where = ['id' => $adminData['id']];
                    $this->cModel->where($where)->setField('last_time',time());
                    session('adminId', $adminData['id']);
                    session('lifetime_start',time());
                    write_log(lang('login_success'));  
                    return ajaxReturn(lang('login_success'), url('Index/lst'));
                }
            } else{
                return ajaxReturn(lang('user_no_exist'));
            }
        }
            return $this->fetch();
    }
    /**
      * [logout description]
      * 退出登录
      * @return [type] [description]
    */
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        Session::flush();
        session(null); 
        $this->redirect('admin/Login/login');
    }


}
