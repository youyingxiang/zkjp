<?php
namespace expand;
class Zkapi
{
	/**
	 * @author  游兴祥
	 * @date    2017/9/28
	 */
	private $en_login_url1           = '/apiv1/enaccount/login.action';    					//员工登录URL
				
	private $customer_login_url      = '/apiv2/account/login';								//客户登录URL
	
	private $account_verify_url      = '/apiv1/account/vertify.action';					    //验证帐号

	private $vertify_email_url		 = '/apiv1/enaccount/vertify.action';					//邮箱验证

	private $update_password_url	 = '/apiv1/enaccount/updatePwd.action';					//修改密码
	
	private $zh_register_url    	 = '/apiv1/account/register.action';					//员工注册中文

	private $ticket_comment_url      = '/apiv1/enticket/comment.action';	

	private $en_register_url         = '/apiv1/enaccount/register.action';					//员工注册英文

	private $select_city			 = '/apiv1/account/selectCity.action';					//加载城市

	private $repair_city_url		 = '/apiv1/enrepair/selectAddress.action';				//维修登记下拉城市选择	

	private $showCity_url			 = '/apiv1/enrepair/showCity.action';					//显示城市

	private $update_userinfo_url	 = '/apiv1/enaccount/updateInfo.action';				//修改用户信息

	private $feedback_url			 = '/apiv1/enfeedback/save.action';						//意见反馈

	private $applyAdvanced_url		 = '/apiv1/account/applyAdvanced.action';				//普通用户成为高级用户

	private $customized_query_url    = '/apiv1/encustomized/list.action';					//定制查询

	private $resetByEmail_url		 = '/apiv1/enaccount/resetByEmail.action';				//通过邮箱修改密码		

	private	$reset_pwd_url			 = '/apiv1/enaccount/resetPwd.action';					//密码重置

	private $applyPwdBack_url		 = '/apiv1/enpwdBack/applyPwdBack.action';				//设备管理员密码找回

	private $faqCategory_url		 = '/apiv1/faqCategory/tree.action';					//常见问题分类

	private $productCategory_url	 = '/apiv1/faq/productCategory.action';					//信息化系统中读取产品类别信息

	private $productName_url		 = '/apiv1/faq/productName.action';						//产品名称信息

	private $productModel_url        = '/apiv1/faq/productModel.action';					//信息化系统中读取产品型号信息

	private $faq_lst_url			 = '/apiv1/faq/list.action';							//常见问题列表	
	
	private $uploadFile_url			 = '/apiv1/upload/uploadFile.action';					//上传文件

	private $applyConsult_url 		 = '/apiv1/enconsult/applyConsult.action';				//项目咨询

	private $applyInquiry_url		 = '/apiv1/eninquiry/applyInquiry.action';				//客户询价

	private $repair_save_url 		 = '/apiv1/enrepair/save.action';						//维修登记

	private $apply_reservation_url	 = '/apiv1/reservation/applyReservation.action';		//预约上门服务申请

	private $ticket_save_url	     = '/apiv1/enticket/save.action';						//问题提交

	private $repair_list_url         = '/apiv1/enrepair/list.action';						//维修登记查询

	private $repair_detail_url		 = '/apiv1/enrepair/detail.action';						//维修登记详情
	
	private $ticket_query_url 		 = '/apiv1/enticket/list.action';						//问题查询

	private $ticket_detail_url 		 = '/apiv1/enticket/detail.action';						//问题详细

	private $ticket_reply_url 		 = '/apiv1/enticket/reply.action';						//问题回复

	private $comment_help_url		 = '/apiv1/enticket/commentHelpful.action';				//评论回复是否有帮助

	private $reservation_query_url	 = '/apiv1/reservation/reservationList.action';			//预约查询

	private $reservation_detail_url	 = '/apiv1/reservation/reservationDetail.action';		//预约详细

	private $ticket_notfinished_url  = '/apiv1/enticket/notFinished.action';				//未完成订单

	private $repair_notfinished_url	 = '/apiv1/enrepair/notFinished.action';				//未完成维修

	private $reserv_notfinished_url	 = '/apiv1/enreservation/notFinished.action';			//未完成预约

	private $problemCategory_url	 = '/apiv1/enfeedback/problemCategory.action';			//反馈意见分类

	private $tproblemCategory_url    = '/apiv1/enticket/problemCategory.action';			//问题分类

	private $get_country_url		 = '/apiv1/enaccount/selectCountry.action';				//获取国家

	private $quire_url				 = '/apiv1/enenquire/save.action';						//客户询盘

	private $customized_detail_url   = '/apiv1/encustomized/detail.action';					//询盘详细

	private $enpartner_save_url 	 = '/apiv1/enpartner/save.action';						//申请成为合作伙伴

	private $entraining_save_url     = '/apiv1/entraining/save.action';						//用户在官网提交培训申请

	private $enenquire_gettoken_url  = '/apiv1/enenquire/getToken.action '; 				//获取token

	private $lang 					 = "zh-CN";												//语言默认zh-CN
	
	private $tz 					 = "+8:00";												//时间
	
	private $platform 				 = "zkweb";												//平台如APP WEB PC等
	
	private $agent					 = "ZK_Center";											//代理商编码码默认为ZK
	
	private $intfVer 				 = "1.0.0";												//接口版本号默认为1.0.0
	
	private $sys 					 = "ZK_Center";											//中控官网对接

	// private $web 					 = 'https://39.108.244.25:8445';							//网站域名
	// private $web 					 = 'https://service.zkteco.com';							//网站域名
	private $web 					 = 'https://150.109.106.119:8443';

	private $sn_check_url			 = 'http://121.12.147.155:7186/SNService.asmx/GetBarInfo';
//	private $sn_check_url			 = 'https://service.zkteco.com/SNService.asmx/GetBarInfo';
	private $sessionId;																		//sessionId会话ID有用到则传

	private $array_currency;
	
	public  function __construct()
	{
		$this->array_currency=array(
			'agent'    => $this->agent,
			'intfVer'  => $this->intfVer,
			'lang'     => $this->lang,
			'platform' => $this->platform,
			'sys'      => $this->sys,
			'tz'       => $this->tz,
			);
	} 
	/**
	 * [notfinished_ticket 未完成订单]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function notfinished_ticket($sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = [
				'params'=>[
					'id' => "",
				],
				'datafmt'=>'1'
			];
		$res = $this->httpRequest($this->web.$this->ticket_notfinished_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	//客户询盘
	public function quire_save($val)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $val['sessionId'];
		unset($val['sessionId']);
		$data['payload'] = [
				'params'=>$val,
				'datafmt'=>'1'
			];
		$res = $this->httpRequest($this->web.$this->quire_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	//申请合作伙伴
	public function enpartner_save($val)
	{
		$data = $this->array_currency;
		$data['payload'] = [
				'params'=>$val,
				'datafmt'=>'1'
			];
		$res = $this->httpRequest($this->web.$this->enpartner_save_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	//用户在官网提交培训申请
	public function entraining_commit($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->entraining_save_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}

	//工单提交评论
	public function ticket_comment($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->ticket_comment_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [notfinished_ticket 未完成维修]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function notfinished_repair($sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = [
				'params'=>[
					'id' => "",
				],
				'datafmt'=>'1'
			];
		$res = $this->httpRequest($this->web.$this->repair_notfinished_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [notfinished_ticket 未完成预约]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function notfinished_reservation($sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = [
				'params'=>[
					'id' => "",
				],
				'datafmt'=>'1'
			];
		$res = $this->httpRequest($this->web.$this->reserv_notfinished_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [get_faqcategory 获取常见问题分类]
	 * @return [type] [description]
	 */
	public function get_faqcategory($val)
	{
		$data = $this->array_currency;
		$data['payload'] = [
				'params'=>['id'=>$val],
				'datafmt'=>'1'
			];
		$res = $this->httpRequest($this->web.$this->faqCategory_url,'POST',json_encode($data),$this->getHeaders());
		return $res;	
	}
	/**
	 * [get_faq_lst 根据分类获取常见问题列表]
	 */
	public function get_faq_lst($val)
	{
		$data = $this->array_currency;
		$data['payload'] = [
				'params'=>$val,
				'datafmt'=>'1'
			];
		$res = $this->httpRequest($this->web.$this->faq_lst_url,'POST',json_encode($data),$this->getHeaders());
		return $res;	
	}
	public function get_country()
	{
		$url = $this->web.$this->get_country_url;
		$res = $this->http_get($url);
		return $res;
	}
	/**
	 * [get_problem_category 获取问题工单的分类]
	 * @return [type] [description]
	 */
	public function get_tproblem_category()
	{
		$url = $this->web.$this->tproblemCategory_url;
		$res = $this->http_get($url);
		return $res;
	}
	/**
	 * [get_problem_category 获取反馈意见的分类]
	 * @return [type] [description]
	 */
	public function get_problem_category()
	{
		$url = $this->web.$this->problemCategory_url;
		$res = $this->http_get($url);
		return $res;
	}
	/**
	 * [get_productcategory 获取faq 产品类别信息]
	 * @return [type] [description]
	 */
	public function get_productCategory()
	{
		$url = $this->web.$this->productCategory_url;
		$res = $this->http_get($url);
		return $res;
	}

	public function get_token()
	{
		$url = $this->web.$this->enenquire_gettoken_url;
		$res = $this->http_get($url);
		return $res;
	}
	/**
	 * [get_productName 产品名称]
	 * @return [type] [description]
	 */
	public function get_productName()
	{
		$url = $this->web.$this->productName_url;
		$res = $this->http_get($url);
		return $res;
	}
	/**
	 * [get_productModel 产品型号]
	 * @return [type] [description]
	 */
	public function get_productModel()
	{
		$url = $this->web.$this->productModel_url;
		$res = $this->http_get($url);
		return $res;
	}  
	/**
	 * [get_login 邮箱手机登录]
	 * @param  [type] $value [帐号密码]
	 * @return [type]        [接口结果]
	 */
	public function get_login($value)
	{
		$data = $this->array_currency;
		$data['payload'] = array(
			'params'=>array(
				'email'=>$value['email'],
				'tel'=>$value['tel'],
				"password"=>md5(md5($value['password']))
				),
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->en_login_url1,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [ticket_query 问题查询]
	 * @return [type] [description]
	 */
	public function ticket_query($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->ticket_query_url,'POST',json_encode($data),$this->getHeaders());
		return $res;	
	}
	public function ticket_detail($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>["someId"=>$val],
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->ticket_detail_url,'POST',json_encode($data),$this->getHeaders());
		return $res;	
	}

	public function customized_detail($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>["doccode"=>$val],
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->customized_detail_url,'POST',json_encode($data),$this->getHeaders());
		return $res;	
	}
	
	/**
	 * [ticket_reply 问题回复]
	 * @param  [type] $val       [description]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function ticket_reply($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->ticket_reply_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [comment_help 评论评论是否有帮助]
	 * @return [type] [description]
	 */
	public function comment_help($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$val['someId'] = intval($val['someId']);
		$val['isHelpful'] = intval($val['isHelpful']);
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->comment_help_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [apply_reservation 预约上面服务申请]
	 * @param  [type] $val       [description]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function apply_reservation($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->apply_reservation_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [ticket_save 问题提交]
	 * @param  [type] $val       [description]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function ticket_save($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->ticket_save_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	} 
	/**
	 * [apply_inquiry 客户询价]
	 * @param  [type] $val       [description]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function apply_inquiry($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		foreach ($val['productList'] as $key => $value) {
			$val['productList'][$key]['productNum'] = intval($value['productNum']);
		}
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->applyInquiry_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [repair_save 维修登记]
	 * @param  [type] $val       [参赛]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function repair_save($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->repair_save_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [repair_query description]
	 * @param  [type] $val       [数组值]
	 * @param  [type] $sessionId [session]
	 * @return [type]            [description]
	 */
	public function repair_query($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->repair_list_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}

	/**
	 * [repair_detail 维修订单详情]
	 * @param  [type] $val       [description]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function repair_detail($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>['doccode'=>$val],
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->repair_detail_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}

	/**
	 * [repair_query 定制查询]
	 * @param  [type] $val       [数组值]
	 * @param  [type] $sessionId [session]
	 * @return [type]            [description]
	 */
	public function customized_query($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->customized_query_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [reservation_query 预约查询]
	 * @param  [type] $val       [description]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function reservation_query($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->reservation_query_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [reservation_detail 预约详细]
	 * @param  [type] $val       [description]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function reservation_detail($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>['orderNumber'=>$val],
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->reservation_detail_url,'POST',json_encode($data),$this->getHeaders());
		return $res;

	}
	/**
	 * [apply_consult 项目咨询]
	 * @param  [type] $data      [提交数据]
	 * @param  [type] $sessionId [description]
	 * @return [type]            [description]
	 */
	public function apply_consult($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
	    $res = $this->httpRequest($this->web.$this->applyConsult_url ,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [get_login_2 手机验证码登录]
	 * @param  [type] $tel [电话号码]
	 * @return [type]      [接口结果]
	 */
	public function get_login_2($tel)
	{
		$data = $this->array_currency;
		$data['payload'] = array(
			'params'=>array(
				'tel'=>$tel,
				),
			'datafmt'=>'1',
			);	
		$res = $this->httpRequest($this->web.$this->zh_logintel_url1,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [vertify_email 邮箱验证]
	 * @param  [type] $email [description]
	 * @return [type]        [description]
	 */
	public function vertify_email($email)
	{
		$data = $this->array_currency;
		$data['payload'] = array(
			'params'=>array(
				'email'=>$email,
				),
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->vertify_email_url,'POST',json_encode($data),$this->getHeaders());
		return $res;	
	}
	/**
	 * [get_register_zh 英文网站注册]
	 * @param  [type] $value [注册数据]
	 * @return [type]        [接口结果]
	 */
	public function get_register_en($value)
	{
		$data = $this->array_currency;
		$password = md5(md5($value['password']));
		$value['password'] = $password;
		$data['payload'] = [
			'params'=>$value,
			'datafmt'=>'1',
			];
		$res = $this->httpRequest($this->web.$this->en_register_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [reset_byemail 通过邮箱修改密码]
	 * @param  [type] $val [邮箱]
	 * @return [type] [接口结果]
	 */
	public function reset_byemail($val)
	{
		$data = $this->array_currency;
	    $data['payload'] = array(
			'params'=>array(
				'email'=>$val,
				),
			'datafmt'=>'1',
			);
	    $res = $this->httpRequest($this->web.$this->resetByEmail_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [apply_pwdback 设备管理员密码找回]
	 * @return [type] [description]
	 */
	public function apply_pwdback($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
	    $res = $this->httpRequest($this->web.$this->applyPwdBack_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [reset_pwd 重置密码]
	 * @param  [type] $val [description]
	 * @return [type]      [description]
	 */
	public function reset_pwd($val)
	{
		$data = $this->array_currency;	
		$val['newPassword'] = md5(md5($val['newPassword']));
		$data['payload'] = array(
			'params'=>$val,
			'datafmt'=>'1',
			);
		$res = $this->httpRequest($this->web.$this->reset_pwd_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [get_upload_info 上传接口]
	 * @param  [type] $file [description]
	 * @return [type]       [description]
	 */
	public function get_upload_info($file)
	{
		// $res = $this->httpRequest($this->web.$this->uploadFile_url,'POST',$file);
		// file_put_contents('test',json_encode($res));
		// exit;
		// return $res;
	}
	/**
	 * [get_city 加载城市]
	 * @author [name] <[游兴祥]>
	 * @return [type] [description]
	 */
	public function get_city($id = '')
	{
		$data = $this->array_currency;
	    $data['payload'] = array(
			'params'=>array(
				'id'=>$id,
				),
			'datafmt'=>'1',
			);
	    $res = $this->httpRequest('http://39.108.244.25:8888'.$this->select_city,'POST',json_encode($data),$this->getHeaders());
	    return $res;
	}
	/**
	 * [update_userinfo 修改用户信息]
	 * @param  [type] $value 		[用户信息数组]
	 * @param  [type] $sessionId 	[登录记录sessionId]
	 * @return [type]        		[接口信息]
	 */
	public function update_userinfo($val,$sessionId)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $sessionId;
		$val['gender'] = intval($val['gender']);
		$data['payload'] = [
				'params'=> $val,
				'datafmt'=>'1',
			];
		$res = $this->httpRequest($this->web.$this->update_userinfo_url,'POST',json_encode($data),$this->getHeaders());
	    return $res;
	}
	/**
	 * [update_password 修改密码]
	 * @param  [type] $value 		[新旧密码]
	 * @param  [type] $sessionId 	[登录记录sessionId]
	 * @return [type]        		[接口信息]
	 */
	public function update_password($val,$sessionId)
	{
		$data = $this->array_currency;
		$val['oldPassword'] = md5(md5($val['oldPassword']));
		$val['newPassword'] = md5(md5($val['newPassword']));
		$data['sessionId'] = $sessionId;
		$data['payload'] = [
				'params'=> $val,
				'datafmt'=>'1',
			];
		$res = $this->httpRequest($this->web.$this->update_password_url,'POST',json_encode($data),$this->getHeaders());
	    return $res;
	}
	/**
	 * [get_sn 查询序列号]
	 * @param  [type] $code [sn值]
	 * @return [type]       [查询结果]
	 */
	public function get_sn($code)
	{
		$url = $this->sn_check_url."?callkey=20170927SNService&barcode=".$code;
		$res = $this->http_get($url);
		return $res;
	}
	/**
	 * [get_verify 获取手机验证结果]
	 * @param  [type] $tel [手机号码]
	 * @return [type]      [验证结果]
	 */
	public function get_verify($tel)
	{
	   $data = $this->array_currency;
	   $data['payload'] = array(
			'params'=>array(
				'tel'=>$tel,
				),
			'datafmt'=>'1',
			);
	   $res = $this->httpRequest($this->web.$this->account_verify_url,'POST',json_encode($data),$this->getHeaders());
	   return $res;
	}
	/**
	 * [getProductCategory 获取产品的类型ID]
	 * @return [type] [description]
	 */
	public function get_product_category()
	{
		$data = $this->array_currency;
		$data['payload'] = array(
			'params'=>array(),
			'datafmt'=>'1',
			);
		$data = json_encode($data);
		$res = $this->http_post($this->get_tree_url,$data);
		return $res;
	}
	/**
	 * [commit_feedback 提交反馈意见]
	 * @param  [type] $val [数组]
	 * @return [type]      [description]
	 */
	public function commit_feedback($val)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $val['sessionId'];
		$data['payload'] = [
			'params'=>[
				'subjectCategory'=>$val['subjectCategory'],
				'problemDesc'=>$val['problemDesc']
			],
			'datafmt'=>'1',
			];
		$res = $this->httpRequest($this->web.$this->feedback_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [commit_applyAdvanced 普通用户申请成为高级用户]
	 * @param  [type] $val [description]
	 * @return [type]      [description]
	 * @author  yxx 
	 */
	public function commit_applyAdvanced($val)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $val['sessionId'];
		$data['payload'] = [
			'params'=>[
				'orgNumber'=>$val['organization_number'],
				'companyName'=>$val['company_name']
			],
			'datafmt'=>'1',
			];
		$res = $this->httpRequest($this->web.$this->applyAdvanced_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [get_showcity 获取显示城市]
	 * @param  [type] $val [description]
	 * @return [type]      [description]
	 */
	public function get_showcity($val)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $val;
		$data['payload'] = [
			'params'=>[
				"cityName"=>''
			],
			'datafmt'=>'1',
			];
		$res = $this->httpRequest($this->web.$this->showCity_url,'POST',json_encode($data),$this->getHeaders());
		return $res;
	}
	/**
	 * [get_repair_branch 根据城市获取分店]
	 * @param  [type] $val [description]
	 * @return [type]      [description]
	 */
	public function get_repair_branch($val)
	{
		$data = $this->array_currency;
		$data['sessionId'] = $val['sessionId'];
		$data['payload'] = [
			'params'=>[
				"city"=>$val['cityName']
			],
			'datafmt'=>'1',
			];
		$res = $this->httpRequest($this->web.$this->repair_city_url,'POST',json_encode($data),$this->getHeaders());
		return $res;	
	}
	/**
	 * CURL请求
	 * @param $url 请求url地址
	 * @param $method 请求方法 get post
	 * @param null $postfields post数据数组
	 * @param array $headers 请求header信息
	 * @param bool|false $debug  调试开启 默认false
	 * @return mixed
	 */
  	public function httpRequest($url, $method="GET", $postfields = null, $headers = array(), $debug = false) {

	    $method = strtoupper($method);
	    $ci = curl_init();
	    /* Curl settings */
	    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
	    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
	    curl_setopt($ci, CURLOPT_TIMEOUT, 100); /* 设置cURL允许执行的最长秒数 */
	    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
	    switch ($method) {
	        case "POST":
	            curl_setopt($ci, CURLOPT_POST, true);
	            if (!empty($postfields)) {
	                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
	                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
	            }
	            break;
	        default:
	            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
	            break;
	    }
	    $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
	    curl_setopt($ci, CURLOPT_URL, $url);
	    if($ssl){
	        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
	        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
	    }
	    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
	    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
	    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
	    /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
	    $response = curl_exec($ci);
	    $requestinfo = curl_getinfo($ci);
	    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
	    if ($debug) {
	        echo "=====post data======\r\n";
	        var_dump($postfields);
	        echo "=====info===== \r\n";
	        print_r($requestinfo);
	        echo "=====response=====\r\n";
	        print_r($response);
	    }
	    curl_close($ci);
	    return $response;
		//return array($http_code, $response,$requestinfo);
	}


	public function getHeaders()
	{
		$headers = array(
			"Content-Type: application/json;charset=utf-8", 
			"Accept: application/json", 
			"Cache-Control: no-cache", 
			"Pragma: no-cache", 
			);
		return $headers;
	}
	/**
	 * GET 请求
	 * @param string $url
	*/
	private function http_get($url){
		$oCurl = curl_init();
		if(stripos($url,"https://")!==FALSE){
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if(intval($aStatus["http_code"])==200){
			return $sContent;
		}else{
			return false;
		}
	}


}
