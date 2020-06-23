<?php
namespace app\index\controller;
use think\Controller;
use expand\Zkapi;
use think\validate;
use app\admin\model\Cases as cases_m;
use app\admin\model\Scheme as scheme_m;
class Solution extends Base
{
    private $zkapi;
    public function _initialize()
    {   
        parent::_initialize();
        $this->zkapi = new Zkapi;
    }
    /**
     * [cases_detail 案例详情]
     */
    public function cases_detail($id = 0)
    {
        if ($id>0) {            //判断产品的id是否存在
            $c = new cases_m;
            $where['state'] = ['eq',1];
            $where['id'] = ['eq',intval($id)];
            $data = $c->where($where)->find();
            if (count($data)>0) {
                $this->assign('detailData',$data);
                $this->setPageInfo(
                    $data['name'],
                    $data['seo_keyword'], 
                    $data['seo_des'],
                    ['public','swiper.min','page','page02'],
                    ['jquery.min','swiper.min','page','layui','layer/layer']
                    );
                cache(request('get')->url(),$this->fetch());
                return $this->fetch();
            }
        }
        return $this->fetch('layout/404');
    }
    /**
     * [cases_lst 案例列表]
     * @return [type] [description]
     */
    public function cases_lst()
    {

        $c = new cases_m;
        $scheme_id = input('get.scheme_id')?input('get.scheme_id'):'';
        if ($scheme_id) $where['scheme_id'] = ['eq',$scheme_id];
        $where['state'] = ['eq',1];

        $data = $c->field('id,name,img,case_des')->where($where)->order('order_key')->paginate(6, false, page_param() );
      
        $this->assign('lstData',$data);
        $scheme = db('scheme')->field('id,name')->where(['state'=>['eq',1]])->order('order_key asc')->select();
        $this->assign('scheme_',$scheme);
        $this->setPageInfo(
            lang('cases_lst'),
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','page','page02'],
            ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();    
    }
    //解决方案列表
    public function scheme_lst()
    {
        $s = new scheme_m;
        if (!empty(input('get.type')) && in_array(input('get.type'),[1,2])) {
            $where['type'] = ['eq',input('get.type')];
        }
        $where['state'] = ['eq',1];
        $data = $s->field('id,name,scheme_img,scheme_des,seo_des')->where($where)->order('order_key')->paginate(6, false, page_param());
        $this->assign('lstData',$data);
        $this->setPageInfo(
            lang('scheme_lst'),
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','page','page02'],
            ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();

    } 
    /**
     * [scheme_detail 方案详情]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function scheme_detail($id = 0)
    {
        if ($id>0) {            //判断产品的id是否存在
            $s = new scheme_m;
            $where['state'] = ['eq',1];
            $where['id'] = ['eq',intval($id)];
            $data = $s->where($where)->find();
            if (count($data)>0) {
                $banner = db('category_banner')->where(['type'=>['eq','scheme'],'cat_id'=>['eq',$id]])->value('img');
                $this->assign('detailData',$data);
                $this->assign('banner',$banner);
                $this->setPageInfo(
                    $data['name'],
                    $data['seo_keyword'], 
                    $data['seo_des'],
                    ['public','swiper.min','page','page02'],
                    ['jquery.min','swiper.min','page','layui','layer/layer']
                    );
                cache(request('get')->url(),$this->fetch());
                return $this->fetch();
            }
        }
        return $this->fetch('layout/404');
    }

    /**
     * [project_consultation 项目咨询]
     */
    public function project_consultation()
    {
        if (request()->isPost()){
            $data = input('post.');
            cookie('input_datap',null);
            cookie('input_datap',$data);
            $validate = new Validate(
            [
                'contactName'  => 'require|max:128',
                'companyName' => 'require|max:128',
                'email' => 'require|email',
                'tel'  => 'require',
            ],
            [
                'contactName.require' => '{%contactName_r}',
                'contactName.max' => '{%contactName_m}',
                'companyName.require' => '{%companyName_r}',
                'companyName.max' => '{%companyName_m}',
                'tel.require' => '{%tel_r}',
                'email' => '{%email_v}',
                'email.require' => '{%email_r}',            
            ]);
            if (!captcha_check($data['verify'])) {                                            //检验验证码
                cookie('project_commit_err',json_encode(lang('code_error')));
                $this->redirect('/project_consultation');
                exit;
            }
            if (!$validate->check($data)) {
                cookie('project_commit_err',json_encode($validate->getError()));
                $this->redirect('/project_consultation');
                exit;
            } else {
                $data['address'] = empty($data['city'])?$data['country']:$data['country'].','.$data['city'];
                $response = json_decode($this->zkapi->apply_consult($data,session('user_flag')));
                if ($response->code == '00000000') {
                    cookie('input_datap',null);
                    $this->redirect('index/Solution/project_consultation_finish');
                    exit;
                } else {
                    cookie('project_commit_err',json_encode($response->msg));
                    $this->redirect('/project_consultation');
                    exit;
                }  
            }
        }
        if (empty(cookie('input_datap'))) {                    //第一次获取个人信息
            cookie('input_datap',json_decode(cookie('user_info'),true));            //获取用户信息
        } 
        $response = json_decode($this->zkapi->get_country(),true);  
        $country = $response['payload']['results']['dataList'];
        $this->assign('country',$country);
        $this->setPageInfo(
                lang('project_consultation'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        return $this->fetch();
    }
    //项目咨询提交完成
    public function project_consultation_finish()
    {
        $this->setPageInfo(
                lang('project_consultation_finish'), 
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        return $this->fetch();
    }
}
