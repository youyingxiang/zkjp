<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\validate;
use expand\Zkapi;
use app\admin\model\ApplyPartner as ap;
class Partner extends Base
{	
	/**
	 * [函数名称]	index
	 * [功能]		获取后台为推荐标记的 合作伙伴新闻 案例新闻
	 * [目地]		展示合作伙伴界面	
	 * [时间]		2017/10/14
	 * [作者]		游兴祥
	 */
	public function index()
	{	
		$ids = [48,49];
		$state = Db::name('category')->where(['id'=>['in',$ids],'state'=>['eq',1]])->count();
		if ($state) {
			$data = Db::name('news')->field('typeid,id,title')
									->where(['typeid'=>['in',$ids],'state'=>['eq',1],'flag'=>['exp',"like '%b%'"]])
									->order('id desc')
									->select();	
		} else {
			$data = [];
		}
		if ($data) {
			$res = array_changekey($data,'typeid');
			ksort($res);
			$data = $res;
		}
		$this->assign('data',$data);
		$this->setPageInfo(
                lang('partner'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [partner_benefit 合作伙伴好处]
	 */
	public function partner_benefit()
	{
		$this->setPageInfo(
                lang('partner_benefit'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [partner_introduce 合作伙伴介绍]
	 */
	public function partner_introduce()
	{
		$this->setPageInfo(
                lang('partner_introduce'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [函数名称]	partner_step2
	 * [功能]		保存第一步数据 在第二步骤进行提交
	 * [目地]		完成第-步骤跳转	
	 * [时间]		2017/10/14
	 * [作者]		游兴祥
	 */
	public function partner_step1()
	{
		if (!empty(session('user_flag'))) {
			$data = json_decode(cookie('user_info'),true);
			$data['position'] = $data['industry'];
			$data['website'] = '';
			cookie('input_data',$data);
		}
		$z = new Zkapi;
		$response = json_decode($z->get_country(),true); 
		$country = $response['payload']['results']['dataList'];
		$this->assign('country',$country);
		$this->setPageInfo(
                lang('partner_step1'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		return $this->fetch();
	}
	/**
	 * [函数名称]	partner_step2
	 * [功能]		验证提交数据  进行插入数据库
	 * [目地]		完成第二步骤跳转	
	 * [时间]		2017/10/14
	 * [作者]		游兴祥
	 */
	public function partner_step2()
	{
		$validate = new Validate(
			[
			    'companyName'  => 'require|max:128|token',
			    'contactName' => 'require|max:128',
			    'tel'	=> 'require',
			    'email'	=> 'require|email',
			],
			[
				'companyName.require' => '{%company_name_r}',
				'companyName.max' => '{%company_name_m}',
				'contactName.require' => '{%name_r}',
				'contactName.max' => '{%name_m}',
				'tel.require'	=> '{%phone_r}',
				'email'	=> '{%email_v}',
				'email.require'	=> '{%email_r}',			
			]);
		if (input('post.merchant')) {
			//在第二步骤点击提交
			$data2 = input('post.');							//获取提交的数据
            (!isset($data2['country']) || empty($data2['country'])) && $data2['country'] = 'USA';
			$data3 = cookie('input_data');						//在第一部提交成功到cookie的数据
			$data3['partnerType'] = $data2['merchant'];			//第一步骤第二步骤数据合二为一
			$data3['productLine'] = $data2['intersted'];
			$data3['demand'] = $data2['reason'];
			$data3['__token__'] = $data2['__token__'];
			cookie('input_data',$data3);														//第一步数据放到cookie
			if (!$validate->check($data3)) {
			    cookie('next_step_err',json_encode($validate->getError()));
                cookie('input_data',null);
                echo json_encode(array('code'=>'403'));
                exit;
			//    $this->redirect('/partner_step1');
			} else {
				$z = new Zkapi;
				unset($data3['__token__']); 
				foreach ($data3 as $key => $value) {
                	$data3[$key] = RemoveXSS($value);
            	}
            	$response = json_decode($z->enpartner_save($data3));

				if ($response->code == '00000000') {										//提交成功
					cookie('input_data',null);
				//	$this->redirect('index/Partner/partner_step_finish');
					echo json_encode($response);
			    	exit;

				}
				else {
					cookie('next_step_err',json_encode($response->msg));
                    cookie('input_data',null);
                    echo json_encode(array('code'=>'404'));
                    //			    $this->redirect('/partner_step1');
                    exit;

				}
			}			
		}
		$data = input('post.');
        (!isset($data['country']) || empty($data['country'])) && $data['country'] = 'USA';
		cookie('input_data',$data);														//第一步数据放到cookie
		if (!$validate->check($data)) {
		    cookie('next_step_err',json_encode($validate->getError()));
		    $this->redirect('/partner_step1');
		    exit;
		} else {
			$this->setPageInfo(
                lang('partner_step2'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
			return $this->fetch();
		}
	}
	/**
	 * [函数名称]	partner_step_finish
	 * [功能]		第二步骤完成后调整界面
	 * [目地]		成功跳转	
	 * [时间]		2017/10/14
	 * [作者]		游兴祥
	 */
	public function partner_step_finish()
	{
		$this->setPageInfo(
                lang('partner_step_finish'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
			return $this->fetch();
	}
}