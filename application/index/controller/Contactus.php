<?php
namespace app\index\controller;
use think\Controller;
use expand\Zkapi;
use think\validate;
use app\admin\model\Contactus as contactus_m;
class Contactus extends Base
{
    /**
     * [index 联系我们]
     * @return [type] [description]
     */
    public function index()
    {
        $this->setPageInfo(
            lang('contactus'),
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','page02','account','join','center','contact'],
            ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    /**
     * [headquarters 总部地址]
     * @return [type] [description]
     */
    public function headquarters_addresss()
    {
        $this->setPageInfo(
            lang('headquarters_addresss'),
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','account','join','center','contact'],
            ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    public function japan_address()
    {
        $this->setPageInfo(
            'ZKTeco Japan Address',
            $this->config_field['keywords']['v'],
            $this->config_field['description']['v'],
            ['public','swiper.min','account','join','center','contact'],
            ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    /**
     * [sale_map 销售地图]
     * @return [type] [description]
     */
    public function sale_map()
    {
        $mapinfo = db('sale_map')->where(['state'=>['eq',1]]) -> order('order_key desc')->select();        //获取地图信息
        $z = new Zkapi; 
        $response = json_decode($z->get_country(),true);  
        $country = $response['payload']['results']['dataList'];
        $this->assign('country',$country);
        $this->assign('mapinfo',json_encode($mapinfo));
        $tokenInfo = json_decode($z->get_token(),true);
        $checkInfo['token'] = $tokenInfo['payload']['results']['data']['token'];
        $checkInfo['sessionId'] = $tokenInfo['sessionId'];
        $this->assign('checkInfo',$checkInfo);
        if (!empty(session('user_flag'))) {
            $data = json_decode(cookie('user_info'),true);
            $this->assign('user_info',$data);
        }

        $this->setPageInfo(
            lang('sale_map'),
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','page','account','join','center','contact','page02'],
            ['jquery.min','swiper.min','salesmap','page','layui','layer/layer']
            );
        return $this->fetch();
    }
    /**
     * [commit_contactus 提交联系我们申请]
     * @return [type] [description]
     */
    public function commit_contactus()
    {
        $data = input('post.');
        $validate = new Validate(
                    [
                        'contactName' => 'require|max:128',
                        'address' => 'require|max:500',
                        'companyName' => 'require|max:255',
                        'email' => 'email',
                        'tel' => 'max:128',
                        'demand'  => 'max:500'
                    ],
                    [
                        'contactName.require'             => '{%name_r}',
                        'contactName.max'                 => '{%name_m}',
                        'address.require'          => '{%address_r}',
                        'address.max'              => '{%address_m}',
                        'email'                    => '{%email_v}',
                        'tel.max'                  => '{%tel_m}',
                        'companyName.require'     => '{%company_name_r}',
                        'companyName.max'         => '{%company_name_m}', 
                        'demand.max'             => '{%need_des_m}'
                    ]);
        if (!$validate->check($data)) {
            return ajaxReturn($validate->getError());
            exit;
        } else {
            $z = new Zkapi;
            foreach ($data as $key => $value) {
                $data[$key] = RemoveXSS($value);
            }
            $response = json_decode($z->quire_save($data));
            if ($response->code == '00000000') {
                // $email_content = cache('config')['email_content2']['v'];     //获取邮箱内容
                // foreach ($data as $k => $v) {
                //     $email_content = str_replace('{$'.$k.'}',$v,$email_content);    //替换模版内容
                // }
                // sendMail(cache('config')['recipients']['v'],lang('apply_cont'),$email_content);
                return ajaxReturn(lang('action_success'),1);
            } else { 
                return ajaxReturn($response->msg);
            }
        }

    }
    /*
     * 服务站点    
     */
    public function service_location()
    {
        //$z = new Zkapi;
        //$province = json_decode($z->get_city());
        $where = array('state'=>'1');
        $arr =  db('service_location')->where($where)->order('order_key asc')->select();
	
       # $arr = json_decode(json_encode($arr),true);
        $province = array();

        foreach($arr as $k => $v)
        {
            $depart_arr = array(
                'id' => $v['id'],
                'departName' => $v['province']
            );
                $province[$v['province']] = $depart_arr ;
        }
        $this->assign('province',$province);
        $this->setPageInfo(
            lang('service_location'),
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','page','page02'],
            ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        return $this->fetch();
    }
    /**
     * [search_cityinfo 根据城市省份  搜索售后服务站点]
     * @return [type] [description]
     */
    public function search_cityinfo()
    {
        $province = trim(input('post.province'));
        $city = trim(input('post.city'));
        if ( empty($city)  ) {
            $where = ['province'=>['eq',$province]]; 
        } else if (!empty($city)) {
            $where = ['city'=>['eq',$city]];
        } else if (empty($city) && empty($province)) {
            return ajaxReturn(lang('please_query'));
        }
        $data = db('service_location')->where($where)->order('order_key asc')->select();
        if (count($data)>0) {
            return ajaxReturn($data,1);
        } else {
            return ajaxReturn(lang('query_null'));
        }
    }

}
