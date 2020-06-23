<?php
namespace app\index\controller;
use think\Controller;
use expand\Zkapi;
use expand\Smsapi;
use think\validate;
use think\Session;
class User extends Base
{
	private $zkapi;
	public function _initialize()
	{	
		parent::_initialize();
		$this->zkapi = new Zkapi;
	}
	/**
	 * [register 第一个注册界面]
	 */
	public function register()
	{	
		$response = json_decode($this->zkapi->get_country(),true);	
		$country = $response['payload']['results']['dataList'];
		$this->assign('country',$country);
		return $this->fetch();
	}
	/**
	 * [verify 验证手机]
	 * @return [type] [接口参赛]
	 */
	public function verify()
	{
		$phone = trim(input('phone_number'));
		$code = trim(input('code'));		
		if (!empty($phone)) {
			if (!captcha_check($code)) {											//检验验证码
                return ajaxReturn(lang('code_error'));
            } else {
                $response = json_decode($this->zkapi->get_verify($phone));			//调用中控验证手机是否注册
                if ($response->code != '00000000') {								//获取中控验证结果
              		return ajaxReturn($response->msg);
              	} else {
              		//session('register_phone',$phone);
             		$sres = sms_send($this->config_field,$phone,trim($this->config_field['templateId_r']['v']),1);           		
             		if ($sres) {
             			session('register_phone',[$sres,$phone]); 
             			return ajaxReturn(lang('action_success'),1);
             		} else {
             			return ajaxReturn(lang('sms_err'));
             		}
              	}                
            }
		}
	}
	/**
	 * [verify2 没有发送验证码的手机验证]
	 */
	public function verify2()
	{
		$phone = trim(input('phone_num'));
		$response = json_decode($this->zkapi->get_verify($phone));			//调用中控验证手机是否注册
      	if ($response->code == '00000000') {								//这里请求成功代表没有注册过这个手机号
      		return ajaxReturn(lang('no_register_login'));
      	} else {
			$sres = sms_send($this->config_field,$phone,trim($this->config_field['templateId_l']['v']),1);
	 		if ($sres) {
	 			session('phone_num_verif',$sres);
	 			return ajaxReturn(lang('action_success'),1);
	 		} else {
	 			return ajaxReturn(lang('sms_err'));
	 		}
	 	}
	}
	/**
	 * [verify3 有发送验证码的手机验证]
	 * @return [type] [description]
	 */
	public function verify3()
	{
		$phone = trim(input('phone_number'));
		$code = trim(input('verif2'));
		if (!empty($phone)) {
			if (!captcha_check($code)) {											//检验验证码
                return ajaxReturn(lang('code_error'));
            } else {
                $response = json_decode($this->zkapi->get_verify($phone));			//验证手机
                if ($response->code == '00000000') {								//这里请求成功代表没有注册过这个手机号
		      		return ajaxReturn(lang('phone_no_register'));
		      	} else {
					$sres = sms_send($this->config_field,$phone,trim($this->config_field['templateId_z']['v']),1);
			 		if ($sres) {
			 			session('phone_num_verif',$sres);
			 			return ajaxReturn(lang('action_success'),1);
			 		} else {
			 			return ajaxReturn(lang('sms_err'));
			 		}
			 	}
			}
		}
	}
	/**
	 * [check_phone_verify 登录手机码验证]
	 */
	public function check_register_verify()
	{
		$code = trim(input('register_verify'));
		$email = trim(input('email'));
		if (!captcha_check($code)) {	
			return ajaxReturn(lang('code_error'));
		} else {
			$email_v = json_decode($this->zkapi->vertify_email($email));
			if ($email_v->code == '00000000') {
				return ajaxReturn(lang('action_success'),1);
			} else {
				return ajaxReturn($email_v->msg);
			}
			
		}
	}
	/**
	 * [register_userinfo 完善用户信息]
	 * @return 
	 */
	public function register_userinfo()
	{
		if (request()->isPost()) {
			$data = input('post.');
			foreach ($data as $key => $value) {
                $data[$key] = RemoveXSS($value);
            }
			$res = json_decode($this->zkapi->get_register_en($data));
			if ($res->code == '00000000') {
				return ajaxReturn(lang('action_success'),1);
			} else {
				return ajaxReturn($res->msg);
			}
		}
	}

	/**
	 * [login 用户登录]
	 * @return [type] [description]
	 */
	public function login()
	{	
		$url = input('get.redirect_url');	
		if (request()->isPost()) {

			$return_data['phone_email'] = '';
			$return_data['phone_num'] = '';

			$url = input('get.redirect_url');
			$data = input('post.');
			if ($data['type'] == 'phone_email') {												//点击手机邮箱登录
				$validate = new Validate(
					[
					    'phone_email'  => 'require|max:50|token',
					    'password' => 'require'
					],
					[
						'phone_email.require' => '{%val_r}',
						'phone_email.max' => '{%val_l}',
						'password.require'	=> '{%val_r}',	
					]);
				if (!$validate->check($data)) {

				    cookie('login_err',json_encode($validate->getError()));

                    if(trim($_COOKIE['login_err']) == "\"The input value can not be empty!\""){
                        cookie('login_err',json_encode('Please input Password!'));
                    }

				    if (isset($data['phone_email']))$return_data['phone_email'] = $data['phone_email'];
					cookie('return_data',$return_data);

				    $this->redirect('index/User/login');
				    exit;
				} else {
					if (check_phone($data['phone_email'])) {									//判断输入的是邮箱还是手机
						$data['email'] ='';
						$data['tel'] = $data['phone_email'];
					} elseif (check_email($data['phone_email'])) {								//判断输入的是邮箱还是手机
						$data['email'] = $data['phone_email'];
						$data['tel'] = '';
					}
					$res = json_decode($this->zkapi->get_login($data));
				#	dd($res);
				}
			} 
			if (!empty($res->code )) {
				if ($res->code == '00000000') {
                    //代表接口没有返回错误
					session('user_flag',$res->sessionId);									//登录成功
					$response_data = $res->payload->results->siteUser;
					cookie('user_info',json_encode($response_data));						//把用户基本信息存到cookie
					cookie('login_s',json_encode($res->msg));
					cookie('return_data',null);						
					if (empty($url)) {
						$this->redirect('/user_center');
					} else {
						header("Location: $url"); 					
						exit;
					}				    
				    exit;
				} else {
					cookie('login_err',json_encode($res->msg));

					if (isset($data['phone_email']))$return_data['phone_email'] = $data['phone_email'];
					cookie('return_data',$return_data);
					if (empty($url)) {
						$this->redirect('index/User/login');
					} else {
						$url = WEB_URL.'/login?redirect_url='.$url;
						header("Location:$url");
						exit;
					}
				    exit;
				}
			} else {
				abort(404,'查无记录');
			}
		}
		$return_data = cookie('return_data');										//登录错误 邮箱手机不需要在输入
		$this->assign('return_data',$return_data);
		$this->setPageInfo(
				lang('login_title'),
				lang('keywords_l'),
				lang('description_l'),
				['public','swiper.min','page','account'],
				['jquery.min','swiper.min','page','layui','layer/layer']
				);
		return $this->fetch();
	}
	/**
	 * [logout 退出登录]
	 * @return [type] [description]
	 */
	public function logout()
	{
		// session_start();
  		//session_unset();
  		//session_destroy();
  		//Session::flush();
        session('user_flag',null);
        cookie('user_info',null);
        empty(cookie('input_data'))?'':cookie('input_data',null);
        empty(cookie('input_datap'))?'':cookie('input_datap',null);
        $url = request()->header('referer');
        $this->redirect($url);
	}
	/**
	 * [clear_logout 退出不跳转]
	 * @return [type] [description]
	 */
	public function clear_logout()
	{
		session('user_flag',null);
        cookie('user_info',null);
        empty(session('login_state_num'))?'':session('login_state_num',null);
        empty(cookie('input_data'))?'':cookie('input_data',null);
        empty(cookie('input_datap'))?'':cookie('input_datap',null); 
	}
	/**
	 * [center 用户中心]
	 * @return [type] [description]
	 */
	public function center()
	{
		$this->setPageInfo(
			lang('user_center'),
			lang('us_keywords'),
			lang('us_des'),
			['public','swiper.min','page','account','join','center']
			);
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [user_info 用户信息]
	 */
	public function user_info()
	{					//检测登录
		if (request()->isPost()) {
			$data = input('post.');
			$response = json_decode($this->zkapi->update_userinfo($data,session('user_flag')));
			if ($response->code == '00000000') {											//登录成功							//更新session
				cookie('user_info',json_encode($response->payload->results->siteUser));		//获取更新数据						
          		$this->redirect('/user_center');
          		exit;           		
          	} else if($response->code == 'ET000001') {					//登陆超时
          		cookie('login_err',json_encode($response->msg));
          		$this->clear_logout();
          		$this->redirect('/login');
          		exit;
          	} else {
          		cookie('user_info_err',json_encode($response->msg));
          		$this->redirect('/user_info');
          		exit;
          	} 
		}
		$user_info = (array)json_decode(cookie('user_info'));

		$this->setPageInfo(
			lang('us_info'),
			$this->config_field['keywords']['v'], 
		    $this->config_field['description']['v'],
			['public','swiper.min','page','account','join','center']
			);
		$response = json_decode($this->zkapi->get_country(),true);	
		$country = $response['payload']['results']['dataList'];
		$this->assign('country',$country);
		$this->assign('user_info',$user_info);
		return $this->fetch();

	}
	/**
	 * [feedback 意见反馈]
	 * @return [type] [description]
	 */
	public function feedback()
	{					//检测登录
		if (request()->isPost()) {
			$code = trim(input('verif'));
			$token = input('post.token');
			if (!captcha_check($code)) {											//检验验证码
               return ajaxReturn(lang('code_error'));
            } else {
            	if (empty(session('user_flag'))) {									//用户没有登陆
            		return ajaxReturn(lang('user_no_login'));
            	} else {
            		if (empty(session('__token__')) || session('__token__') !== $token) {
			            return ajaxReturn('Please refresh the interface!Do not repeat the submission!');    
			        } else {
			            session('__token__',null);
			        }
            		$data = [];
	            	$data['subjectCategory'] = input('subjectCategory');
	            	$data['problemDesc'] = input('problemDesc');
	            	$data['sessionId'] = session('user_flag');
	            	$response = json_decode($this->zkapi->commit_feedback($data));
	            	if ($response->code == '00000000') {								//获取中控验证结果
	              		return ajaxReturn(lang('action_success'),1);              		
	              	} else {
	              		return ajaxReturn($response->msg);
	              	}  
	            }
            } 
		} else {
			$resp = json_decode($this->zkapi->get_problem_category(),true);
			$problem_cat = empty($resp['payload']['results']['dataList'])?'':$resp['payload']['results']['dataList']; 
			$this->assign('problem_cat',$problem_cat);
			$this->setPageInfo(
				lang('feedback'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
			return $this->fetch();
		}

	}
	/**
	 * [feedback_finish 意见反馈完成]
	 * @return [type] [description]
	 */
	public function feedback_finish()
	{
		$this->setPageInfo(
				lang('feedback_finish'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
		return $this->fetch();
	}
	/**
	 * [authentication 权限与认证]
	 * @return [type] [description]
	 */
	public function authentication()
	{
		$userType = json_decode(cookie('user_info'),true)['userType'];
		$this->assign('userType',$userType);
		$this->setPageInfo(
				lang('authentication'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
		return $this->fetch();
	}
	/**
	 * [apply_gold_vip 申请成为黄金会员]
	 * @return [type] [description]
	 */
	public function apply_gold_vip()
	{
		if (request()->isPost()) {
			$code = trim(input('verif'));
			if (!captcha_check($code)) {											//检验验证码
               return ajaxReturn(lang('code_error'));
            } else {
            	if (empty(session('user_flag'))) {									//用户没有登陆
            		return ajaxReturn(lang('user_no_login'));
            	} else {
            		$data = [];
	            	$data['company_name'] = input('company_name');
	            	$data['organization_number'] = input('organization_number');
	            	$data['sessionId'] = session('user_flag');
	            	$response = json_decode($this->zkapi->commit_applyAdvanced($data));
	            	if ($response->code == '00000000') {								//获取中控验证结果
	            		$response_data = $response->payload->results->siteUser;
						cookie('user_info',json_encode($response_data));
	              		return ajaxReturn(lang('action_success'),1);              		
	              	} else {
	              		return ajaxReturn($response->msg);
	              	}  
	            }
	        }

		} else {
			$this->setPageInfo(
					lang('apply_gold_vip'),	
					$this->config_field['keywords']['v'], 
			    	$this->config_field['description']['v'],
					['public','swiper.min','page','account','join','center']
				);
			return $this->fetch();
		}
	}
	/**
	 * [apply_gold_vip_finish 提交黄金会员成功]
	 * @return [type] [description]
	 */
	public function apply_gold_vip_finish()
	{
		$this->setPageInfo(
				lang('apply_gold_finish'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
		return $this->fetch();
	}
	/**
	 * [update_password 修改密码]
	 * @return [type] [description]
	 */
	public function update_password()
	{
		if (request()->isPost()) {
			$data = input('post.');			//提交的旧密码与新密码
			$response = json_decode($this->zkapi->update_password($data,session('user_flag')));
			if ($response->code == '00000000') {
				$this->clear_logout();											//登录成功				
          		$this->redirect('/index/User/update_password_success');
          		exit;           		
          	} else if($response->code == 'ET000001') {					//登陆超时
          		cookie('login_err',json_encode($response->msg));
          		$this->clear_logout();
          		$this->redirect('/login');
          		exit;
          	} else {
          		cookie('upd_pwd_err',json_encode($response->msg));
          		$this->redirect('/update_password');
          		exit;
          	} 
		}
		$this->setPageInfo(
				lang('update_password'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
		return $this->fetch();
	}
	/**
	 * [update_password_success 密码修改成功]
	 */
	public function update_password_success()
	{
		$this->setPageInfo(
				lang('update_password'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
		return $this->fetch();
	}
	/**
	 * [email_retrieva_success 邮箱重置密码发送邮件成功]
	 */
	public function email_retrieva_success()
	{
		$this->setPageInfo(
				lang('email_retrieva_success'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
		$this->assign('email',input('get.email'));
		return $this->fetch();
	}
	/**
	 * [password_retrieval 密码重置成功]
	 */
	public function reset_password_success()
	{
		$this->setPageInfo(
				lang('reset_password_success'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
		return $this->fetch();
	}
		/**
	 * [apply_pwdback_success 设备管理员密码提交成功]
	 * @return [type] [description]
	 */
	public function apply_pwdback_success()
	{
		$this->setPageInfo(
				lang('apply_pwdback_success'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
		return $this->fetch();
	}
	/**
	 * [password_retrieval 找回密码]
	 */
	public function password_retrieval()
	{
		if (request()->isPost()) {
			if (!empty(input('post.verify1'))) {											//验证码1代表是进行邮箱找回
				$code = input('post.verify1');
				$email = input('post.email');
				if (!captcha_check($code)) {											//检验验证码
                	return ajaxReturn(lang('code_error'));
            	} else {
            		$email_v = json_decode($this->zkapi->vertify_email($email));		//验证邮箱是否注册
            		if ($email_v->code !== '00000000') {
	            		$response = json_decode($this->zkapi->reset_byemail($email));		//邮箱找回密码接口
	            		if ($response->code == '00000000') {								//获取中控验证结果
		              		return ajaxReturn(lang('email_send_success'),1);              		
		              	} else {
		              		return ajaxReturn($response->msg);
		              	}
		            } else {
		            	return ajaxReturn(lang('email_no_register'));
		            } 
            	} 
			} 
		}
		$this->setPageInfo(
				lang('password_retrieval'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page','account','join','center']
			);
		return $this->fetch();	
	}

	/**
	 * reset_password 重置密码
	 */
	public function reset_password()
	{
		if (request()->isPost()) {
			if (input('post.token')) {					//邮箱重置
				$data = [];							
				$data['id'] = input('post.id');	
				$data['pwdtoken'] = input('post.token');
				$data['newPassword'] = input('post.newPassword');	
			}
			$response = json_decode($this->zkapi->reset_pwd($data));
			if ($response->code == '00000000') {								//获取中控验证结果	
          		return ajaxReturn(lang('action_success'),1);              		
          	} else {
          		return ajaxReturn($response->msg);
          	}
		} else {
			$this->setPageInfo(
					lang('reset_password'),	
					$this->config_field['keywords']['v'], 
			    	$this->config_field['description']['v'],
					['public','swiper.min','page','account','join','center']
				);
			return $this->fetch();
		}
	}

	/**
	 * [apply_pwdback 设备管理员密码找回]
	 */
	public function apply_pwdback()
	{
		if (request()->isPost()) {
			$data = input('post.');
			$data = json_decode($data['info']);
			$response = json_decode($this->zkapi->apply_pwdback($data,session('user_flag')));
			if ($response->code == '00000000') {								//获取中控验证结果	
          		return ajaxReturn(lang('action_success'),1);              		
          	} else {
          		return ajaxReturn($response->msg);
          	}		
		}
		$this->setPageInfo(
				lang('apply_pwdback'),	
				$this->config_field['keywords']['v'], 
		    	$this->config_field['description']['v'],
				['public','swiper.min','page02','account','join','center']
			);
		return $this->fetch();
	}

	/**
     * [repair_query 维修查询]
     * @return [type] [description]
     */
    public function repair_query()
    {
        $this->setPageInfo(
                lang('repair_query'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','account','join','center']
            );
        return $this->fetch();
    }
    /**
     * [get_state_list 根据状态调用接口获取列表]
     * @return [type] [description]
     */
    public function get_state_list()
    {
    	if (empty(session('user_flag'))) return lang('please_login_err');
    	$value['status'] = input('post.status');
    	$value['curPage'] = intval(input('post.time'));
    	$value['pageSize'] = intval(config('paginate')['list_rows']);  
    	$value['docsn'] = trim(input('post.docsn'));
    	$response = json_decode($this->zkapi->repair_query($value,session('user_flag')),true);

  		if ($response['code'] == '00000000') {   
            if (!empty($response['payload']['results']['dataList'])) {
                $lst = $response['payload']['results']['dataList'];
                $this->assign('lst',$lst);  
                return $this->fetch();
            }
        } else {
            return $response['msg'];
        } 
    }
    /**
     * [zk_repair_detail 维修单号详情]
     * @param  string $doccode [description]
     * @return [type]          [description]
     */
    public function zk_repair_detail($doccode = '0')
    {
    	if (!empty(trim($doccode))) {
    		$response = json_decode($this->zkapi->repair_detail(trim($doccode),session('user_flag')),true);
    		if ($response['code'] == '00000000') {
	    		$detailDate = $response['payload']['results']['data'];
	    		$this->assign('detailDate',$detailDate);
		    	$this->setPageInfo(
		                lang('zk_repair_detail'),  
		                $this->config_field['keywords']['v'], 
		                $this->config_field['description']['v'],
		                ['public','swiper.min','page','account','join','center']
		            );
		     	return $this->fetch();
		    } else {
		    	abort(404,'查无记录');
		    }
	    } else {
	    	abort(404,'查无记录');
	    }
    }
    /**
     * [ticket_query 工单查询]
     * @return [type] [description]
     */
    public function ticket_query()
    {
    	$this->setPageInfo(
                lang('ticket_query'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','account','join','center']
            );
        return $this->fetch();
    }
    /**
     * [get_state_tlist 状态获取问题列表]
     * @return [type] [description]
     */
    public function get_state_tlist()
    {
    	if (empty(session('user_flag'))) return lang('please_login_err');
    	$value = input('post.');
        isset($value['startTime']) && !empty($value['startTime']) && $value['startTime'] = date("Y-m-d",strtotime($value['startTime'])) ;
        isset($value['endTime']) && !empty($value['endTime']) && $value['endTime'] = date("Y-m-d",strtotime($value['endTime'])) ;

    	$value['curPage'] = intval($value['curPage']);

		if(!isset($value['pageSize']) || empty($value['pageSize'])){
			$value['pageSize'] = intval(config('paginate')['list_rows']);
		}else{
			$value['pageSize'] = (int)$value['pageSize']  ;
		}

    	$response = json_decode($this->zkapi->ticket_query($value,session('user_flag')),true);
		//print_r($response);exit;
  		if ($response['code'] == '00000000') {   
            if (!empty($response['payload']['results']['dataList'])) {
                $lst = $response['payload']['results']['dataList'];
                $this->assign('lst',$lst);  
                return $this->fetch();
            }
        } else {
           	return $response['msg'];
        } 
    }
    /**
     * [ticket_detail 问题详细]
     * @return [type] [description]
     */
    public function zk_ticket_detail($someId = '')
    {
    	if (!empty(trim($someId))) {
    		$response = json_decode($this->zkapi->ticket_detail(intval(trim($someId)),session('user_flag')),true);
    		if ($response['code'] == '00000000') { 
	    		$detailData = $response['payload']['results']['data'];
	    		$this->assign('detailData',$detailData);
		    	$this->setPageInfo(
		                lang('zk_ticket_detail'),  
		                $this->config_field['keywords']['v'], 
		                $this->config_field['description']['v'],
		                ['public','swiper.min','page','page02','account','join','center']
		            );
		    	if ($detailData['status'] == 'Finished' || $detailData['status'] == 'Evaluated') {			//状态为已完结
		    		return $this->fetch('zk_ticket_detail_comment');
		    	} else {
		    		return $this->fetch();
		    	}
		     	
		    } else {
		    	abort(404,'查无记录');
		    }
	    } else {
	    	abort(404,'查无记录');
	    }
    }
    /**
     * [reply_commit 问题回复]
     * @return [type] [description]
     */
    public function reply_commit()
    {
    	if (empty(session('user_flag'))) return ajaxReturn(lang('please_login_err'));
    	if (!captcha_check(trim(input('post.verify')))) {                               //检验验证码
            return ajaxReturn(lang('code_error'));
            exit;
        } 
    	$value['someId'] = intval(trim(input('post.someId')));
    	$value['handleList']['contient'] = RemoveXSS(trim(input('post.contient')));			//恢复内容进行过滤
    	$value['handleList']['uploadfile'] = trim(input('post.uploadfile'));
    	$response = json_decode($this->zkapi->ticket_reply($value,session('user_flag')),true);
  		if ($response['code'] == '00000000') {   
            return ajaxReturn($response['msg'],1);
        } else {
           	return ajaxReturn($response['msg']);
        } 
    }
    /**
     * [reply_comment_help 恢复评论是否有帮助]
     * @return [type] [description]
     */
    public function reply_comment_help()
    {
    	if (empty(session('user_flag'))) return ajaxReturn(lang('please_login_err'));
    	$value = input('post.');
    	$response = json_decode($this->zkapi->comment_help($value,session('user_flag')),true);
    	if ($response['code'] == '00000000') {   
            return ajaxReturn($response['msg'],1);
        } else {
           	return ajaxReturn($response['msg']);
        } 
    }
    /**
     * [get_common 局部获取评论]
     * @return [type] [description]
     */
    public function get_common()
    {
    	if (empty(session('user_flag'))) return lang('please_login_err');
    	$someId = input('post.someId');
    	if (!empty(trim($someId))) {
    		$response = json_decode($this->zkapi->ticket_detail(intval(trim($someId)),session('user_flag')),true);
    		$lst = $response['payload']['results']['data']['handleList'];
    		$this->assign('lst',$lst);
	     	return $this->fetch();
	    }
    }
    //提交评论
    public function ticket_commnet(){
    	if (empty(session('user_flag'))) return lang('please_login_err');
    	$data = input('post.');
    	$data['someId'] = intval($data['someId']);
    	$data['commentScore'] = intval($data['commentScore']);
    	$response = json_decode($this->zkapi->ticket_comment($data,session('user_flag')),true);
    	if ($response['code'] == '00000000') {   
            return ajaxReturn($response['msg'],1);
        } else {
           	return ajaxReturn($response['msg']);
        } 
    }
    /**
     * [reservation_query 预约查询]
     * @return [type] [description]
     */
    public function reservation_query()
    {
    	$this->setPageInfo(
                lang('reservation_query'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','account','join','center']
            );
        return $this->fetch();
    }
    /**
     * [get_state_rlist 状态获取预约查询]
     * @return [type] [description]
     */
    public function get_state_rlist()
    {
    	if (empty(session('user_flag'))) return lang('please_login_err');
    	$value['status'] = input('post.status');
    	$value['curPage'] = intval(input('post.time'));
    	$value['pageSize'] = intval(config('paginate')['list_rows']);  
    	$value['orderNumber'] = trim(input('post.docsn'));
    	$response = json_decode($this->zkapi->reservation_query($value,session('user_flag')),true);
  		if ($response['code'] == '00000000') {   
            if (!empty($response['payload']['results']['dataList'])) {
                $lst = $response['payload']['results']['dataList'];
                $this->assign('lst',$lst);  
                return $this->fetch();
            }
        } else {
            return $response['msg'];
        } 
    }
    /**
     * [zk_reservation_detail 预约详细]
     * @return [type] [description]
     */
    public function zk_reservation_detail($orderNumber = '')
    {
    	if (!empty(trim($orderNumber))) {
    		$response = json_decode($this->zkapi->reservation_detail(trim($orderNumber),session('user_flag')),true);
    		if ($response['code'] == '00000000') { 
	    		$detailData = $response['payload']['results']['data'];
	    		$this->assign('detailData',$detailData);
		    	$this->setPageInfo(
		                lang('zk_reserv_detail'),  
		                $this->config_field['keywords']['v'], 
		                $this->config_field['description']['v'],
		                ['public','swiper.min','page','account','join','center']
		            );
		     	return $this->fetch();
		    } else {
		    	abort(404,'查无记录');
		    }
	    } else {
	    	abort(404,'查无记录');
	    }
    }



    /****************英文单独定制查询*****************/

    public function customized()
    {
        $this->setPageInfo(
                lang('customized'),
                $this->config_field['keywords']['v'],
                $this->config_field['description']['v'],
                ['public','swiper.min','page','account','join','center','page02']
            );
        return $this->fetch();
    } 

    public function get_cstate_list()
    {
    	if (empty(session('user_flag'))) return lang('please_login_err');
    	$value['status'] = input('post.status');
    	$value['curPage'] = intval(input('post.time'));
    	$value['pageSize'] = intval(config('paginate')['list_rows']);  
    	$value['doccode'] = trim(input('post.docsn'));
    	$response = json_decode($this->zkapi->customized_query($value,session('user_flag')),true);
		//print_r($response);exit;
  		if ($response['code'] == '00000000') {   
            if (!empty($response['payload']['results']['dataList'])) {
                $lst = $response['payload']['results']['dataList'];
                $this->assign('lst',$lst);
                return $this->fetch();
            }
        } else {
            return $response['msg'];
        } 
    }

    public function zk_customized_detail($doccode = '')
    {

    	if (!empty(trim($doccode))) {
    		$response = json_decode($this->zkapi->customized_detail(trim($doccode),session('user_flag')),true);
		
    		if ($response['code'] == '00000000') { 
	    		$detailData = $response['payload']['results']['data'];
	    		$detailData['uploadFileInfo'] = json_zkpai($detailData['uploadFileInfo']);
	    		$this->assign('detailData',$detailData);
                $this -> assign('download_url','https://service.zkteco.com:8443/commonController.action?viewFile&subclassname=com.framework.web.system.pojo.base.TSDocument&fileid=');
		    	$this->setPageInfo(
		                lang('zk_reserv_detail'),  
		                $this->config_field['keywords']['v'], 
		                $this->config_field['description']['v'],
		                ['public','swiper.min','page','account','join','center','page02']
		            );
		     	return $this->fetch();
		    } else {
		    	abort(404,'查无记录');
		    }
	    } else {
	    	abort(404,'查无记录');
	    }
    }


}
