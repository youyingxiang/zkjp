<?php
namespace app\index\controller;
use think\Controller;
use app\admin\model\News as news_m;
use app\admin\model\Subscribe as subscribe_m;
use think\Db;
class News extends Base
{
    private $cModel;   //当前控制器关联模型
    public function _initialize(){
        parent::_initialize();
        $this->cModel = new news_m;
    }
    public function lst($id = '')
    {
        $id = input('id');
        if ($id) {
            $cateData = get_bykey_find($this->news_cat,'id',$id);
            $crumb_news = get_crumb_news($cateData,$this->news_cat);
            $this->assign('crumb_news',$crumb_news);
            if ($id == 40) {                                                  //服务公告的视图与其他新闻内容视图不同
                $this->setPageInfo(
                    $cateData['name'],
                    $cateData['seo_keyword'],
                    $cateData['seo_des'],
                    ['public','swiper.min','page','page02'],
                    ['jquery.min','swiper.min','page','layui','layer/layer']
                );
                $this->assign('cid',$id);
                return $this->fetch();
            } else if($id == 48) {                                           //合作伙伴
                $this->setPageInfo(
                    $cateData['name'],
                    $cateData['seo_keyword'],
                    $cateData['seo_des'],
                    ['public','swiper.min','page','page02'],
                    ['jquery.min','swiper.min','page','layui','layer/layer']
                );
                $this->assign('cid',$id);
                return $this->fetch('lst_');
            } else {
                $this->setPageInfo(
                    $cateData['name'],
                    $cateData['seo_keyword'],
                    $cateData['seo_des'],
                    ['public','swiper.min','page','center','page02'],
                    ['jquery.min','swiper.min','page','layui','layer/layer']
                    );
                $this->assign('cid',$id);
                return $this->fetch('lst_news');
            }
        } else {
            abort(404,'查无记录');
        }
    }
    /**
     * [get_category_list ajax 获取新闻分类]
     * @param [cid:分类的id]
     * @param [firstRow:第一输入的次数]
     */
    public function get_category_list()
    {   
        $cid = trim(input('cid'));
        $firstRow = intval(trim(input('post.time')));    
        $cateData = get_bykey_find($this->news_cat,'id',$cid);
        $search = trim(input('post.search'));
        if(!$cateData) return '';       //处理分类停用
        if (isset($firstRow)) {
            if(!$cid)$cateData['page'] = 10;
            $firstRow = $firstRow*$cateData['page'];
            $listRows = $cateData['page'];
            $where['state'] = ['eq',1]; 
            if(!empty($search))$where['title'] = ['like',"%".$search."%"];
            if($cid)$where['typeid'] = ['eq',$cid]; 
            $data = $this->cModel->where($where)
                                    ->order('update_time desc')
                                    ->limit($firstRow,$listRows)
                                    ->select(); 
            $this->assign('news_cate_data',$data);
            if ($cid == '40') {              //服务公告
               // cache(request('get')->url(),$this->fetch());
                return $this->fetch();
            } else if($cid == '48') {      //合作伙伴
                //cache(request('get')->url(),$this->fetch());
                return $this->fetch('get_category_list_');
            } else { 
                //cache(request('get')->url(),$this->fetch());
                return $this->fetch('get_category_news_list');
            }
        }
    }
    /**
     * [detail 新闻详细]
     * @param [type] [文章的id]
     */
    public function detail($id = 0)
    {
        if  ($id) {
            $where['state'] = ['eq',1];
            if ($id > 0) {                
                $data = $this->cModel->where($where)->find($id);
            } else {
                $where['url_title'] = ['eq',$id];
                $data = $this->cModel->where($where)->find();
            }
            $new_news = $this->cModel->field('id,title,url_title')->where(['state'=>['eq',1],'typeid'=>['eq',$data['typeid']],'id'=>['neq',$data['id']]])->order('update_time desc')->limit(0,4)->select();
            if (count($data)>0) {
		if (!empty($data['url'])) {
			header('Location:'.$data['url']);
			exit; 
		}
                $type_ = get_bykey_find($this->news_cat,'id',$data['typeid']);
                $crumb_news = get_crumb_news($type_,$this->news_cat);
                $this->assign('crumb_news',$crumb_news);
                $this->assign('detailData',$data);
                $this->assign('new_news',$new_news);
                $this->setPageInfo(
                    $data['title'],
                    $data['seo_keyword'], 
                    $data['seo_des'],
                    ['public','swiper.min','page','page02','center'],
                    ['jquery.min','swiper.min','page','layui','layer/layer']
                    );
                cache(request('get')->url(),$this->fetch('news_detail'));
                return $this->fetch('news_detail');
            }
        }
        abort(404,'查无记录');
    }
    /**
     * 新闻中心
     * @return [type] [description]
     */
    public function news_center()
    {
        $data = Db::name('news')->field('typeid,id,title,url_title')
                ->where(['state'=>['eq',1],'flag'=>['like',"%b%"]])
                ->order('update_time desc')
                ->select();     
        if ($data) {
            $arr_news = get_bycatgoryid_array($data,$this->news_cat,'typeid','id');
           // dd($arr_news);
		$this->assign('arr_news',$arr_news);
        }
        $this->setPageInfo(
            lang('news_center'), 
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','page','center','page02'],
            ['jquery.min','swiper.min','page','layui','layer/layer']
        );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }

    /**
     * [subscribe 订阅咨询]
     * @return [type] [description]
     */
    public function subscribe()
    {
        $have = db('subscribe')->where(['user_id'=>['eq',json_decode(cookie('user_info'),true)['id']]])->column('newscat_id');
        $have_s = [];
        $nohave_s = [];
        foreach ($this->news_cat as $key => $value) {
            if (in_array($value['id'], $have)) {
                $have_s[] = $value;
            } else {
                $nohave_s[] = $value; 
            }
        }
        $this->assign('have_s',$have_s);
        $this->assign('nohave_s',$nohave_s);
        $this->setPageInfo(
                lang('subscribe'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','account','join','center']
            );
        return $this->fetch();
    }
    /**
     * [subscibe_change 订阅的取消与关注]
     * @return [type] [description]
     */
    public function subscibe_change()
    {
        if (empty(session('user_flag'))) return ajaxReturn(lang('please_login_err'));
        $flag = trim(input('post.flag'));
        $user_info = json_decode(cookie('user_info'),true);
        if ($flag == '+')
            $news_cat = json_decode(input('post.news_catId'),true); 
        else 
            $news_cat = trim(input('post.news_catId'));    
        if (count($news_cat)>0) { 
            $s = new subscribe_m;
            if ($flag == "+") {
                $data = [];
                foreach ($news_cat as $key => $value) {
                    $data[$key]['user_id'] = $user_info['id'];
                    $data[$key]['user_email'] =  $user_info['email'];
                    $data[$key]['newscat_id'] = $value;
                }
                $res = $s->saveAll($data);
                if ($res) return ajaxReturn(lang('action_success'),1);else return ajaxReturn(lang('action_error'));
            } else if($flag == '-') {
                $res = $s->where(['user_id'=>['eq',$user_info['id']],'newscat_id'=>['eq',$news_cat]])->delete();
                if ($res !== false) return ajaxReturn(lang('action_success'),1);else return ajaxReturn(lang('action_error'));
            }
        } else {
            return ajaxReturn(lang('action_error'));
        }
    }
}
