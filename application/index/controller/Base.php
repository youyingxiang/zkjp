<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Lang;
use app\admin\model\Category as cat_m;
use app\admin\model\Nav as nav_m;
class Base extends Controller
{
    protected $config_field;
    protected $ad;
    protected $nav;
    protected $product_cat;
    protected $news_cat;

    public function _initialize()
    {
        define('MODULE_NAME', request()->module());
        define('CONTROLLER_NAME', request()->controller());
        define('ACTION_NAME', request()->action());
        !empty(request()->routeInfo()['rule'][0])?$this->auth_hook(request()->routeInfo()['rule'][0]):'';        //检测权限
        visitors();
        //统计访问量
        $this->check_cache_html();                                                                                //执行缓存页面
        $this -> check_user_info();
        $langField = config('default_lang');
        Lang::load(APP_PATH . 'index/lang/'.$langField.'.php');
    	Lang::load(APP_PATH . 'index/lang/'.$langField.'/'.CONTROLLER_NAME.'.php');
        $this->config_field = cache('config')?cache('config'):$this->get_config();
        $this->ad = cache('ad')?cache('ad'):$this->get_ad();
        $this->nav = cache('nav')?cache('nav'):$this->get_nav();
  
        $this->product_cat = cache('product_cat')?cache('product_cat'):$this->get_product_cat();
        $this->file_cat = cache('file_cat')?cache('file_cat'):$this->get_file_cat();
        $this->news_cat = cache('news_cat')?cache('news_cat'):$this->get_news_cat();
        $this->cases = cache('cases')?cache('cases'):$this->get_cases();
        $this->scheme = cache('scheme')?cache('scheme'):$this->get_bycategory_scheme();
        $this->language = cache('language')?cache('language'):$this->get_language();
        $this->auth_hook(request());
	
	$ndata = Db::name('news')->field('typeid,id,title,url_title')
                ->where(['state'=>['eq',1],'flag'=>['like',"%b%"]])
                ->order('update_time desc')
                ->select();
	$arr_news1 = get_bycatgoryid_array($ndata,$this->news_cat,'typeid','id');
	$this->assign('arr_news1',$arr_news1);

	
        $this->assign('language',$this->language);
        $this->assign('nav',$this->nav);
        $this->assign('ad',$this->ad);
        $this->assign('product_cat',$this->product_cat);
        $this->assign('file_cat',$this->file_cat);
        $this->assign('news_cat',$this->news_cat);
        $this->assign('cases',$this->cases);
        $this->assign('scheme',$this->scheme);
        $this->assign('web',$this->config_field);

    }

    /**
     * 检测是否登录
     */
    public function check_user_info(){
        if(isset($_COOKIE['user_info']) && !empty($_COOKIE['user_info'])){
            $this->assign('user_info',(array)json_decode($_COOKIE['user_info']));
        }else{
            $this->assign('user_info',array());
        }
    }

    protected function check_cache_html()
    {
        if (!empty(input('get.gclid'))) {
            echo cache(request('get')->path());
            exit;
        } else if(cache(request('get')->url())) {
            echo cache(request('get')->url());
            exit;
        }
    }
    /**
     * [setPageInfo description]
     * @author [name] <[1365831278@qq.com youxingxiang]>
     * 设置页面信息
     * @param [type] $title           [页面标题]
     * @param [type] $seo_keywords    [seo关键字]
     * @param [type] $seo_description [seo描述]
     * @param array  $css             [css样式包]
     * @param array  $js              [js数组包]
     */
    protected function setPageInfo($title, $seo_keywords, $seo_description,$css = ['public','swiper.min','page','page02'], $js = ['jquery.min','swiper.min','page','layui','layer/layer'])
    {
        $this->assign('title', $title);     
    	$this->assign('seo_keywords', $seo_keywords);
    	$this->assign('seo_description', $seo_description);
    	$this->assign('page_css', $css);
    	$this->assign('page_js', $js);
    }
    //区域语言
    public function get_language()
    {
        $language = db('language_web')->where(['state'=>['eq',1]])->order('order_key asc')->select();
        cache('language',$language);
        return $language;
    }
    /**
     * [getNav 获取导航]
     * @return [type] [导航条数据]
     */
    protected function get_nav()
    {
        $model = new nav_m;
        $data_1 = $model->getTree('order_key asc',['show_position' => ['in','1,3']]);
        $data_2 = $model->getTree('order_key asc',['show_position' => ['in','2,3']]);
        $data=array($data_1,$data_2);
        cache('nav',$data);
        return $data;
    }
    /**
     * [get_config 获取配置]
     * @return [type] [description]
     */
    protected function get_config()
    {
        $config_field = Db::name('config_field')->where(['status'=>['eq',1]])->select();
        $data = [];
        foreach ($config_field as $key => $value) {
            $data[$value['k']] = $value;
        }
        cache('config',$data);
        return $data;
    }

    /**
     * [getAd 获取广告]
     * @return [type] [array]
     */
    protected function get_ad()
    {
        $data = Db::name('ad')->where("state",'1')
                       ->order('order_key asc')
                       ->select();
        $arr = array();     
        foreach ($data as $k => $val) {                  //数组归类
            $arr[$val['display_position']][] = $val;
        }
        cache('ad',$arr);
        return $arr;
    }
    /**
     * [get_product 获取产品分类]
     * @return [type] [description]
     */
    protected function get_product_cat()
    {
        $cat_m = new cat_m;
        $data = $cat_m->getTree('order_key asc',['state'=>['eq',1],'type'=>['eq','product']]);
        cache('product_cat',$data);                
        return $data;
    }
    /**
     * [get_news 获取新闻分类]
     * @return [type] [description]
     */
    protected function get_news_cat()
    {

        $data = Db::name('category')->where(['state'=>['eq',1],'type'=>['eq','news']])
                                    ->order('order_key asc')
                                    ->select();
        cache('news_cat',$data);                                                
        return $data;
    }
    /**
     * [get_file 获取下载分类]
     * @return [type] [description]
     */
    protected function get_file_cat()
    {

        $data = Db::name('category')->where(['state'=>['eq',1],'type'=>['eq','file']])
                                    ->order('order_key asc')
                                    ->select();
        cache('file_cat',$data);                                                
        return $data;
    }
    /**
     * [get_case 获取案例]
     * @return [type] [description]
     */
    protected function get_cases()
    {
        $data = Db::name('cases')->field('id,img_nav,name')
                                 ->where(['state'=>['eq',1],'is_top'=>['eq','1']])
                                 ->order('order_key asc')
                                 ->limit(0,4)
                                 ->select();
        cache('cases',$data);                                                
        return $data;
    }
    /**
     * [get_bycategory_scheme 分类获取方案]
     * @return [type] [description]
     */
    protected function get_bycategory_scheme()
    {
        $data = Db::name('scheme')->field('id,name,type')
                                  ->where(['state'=>['eq',1]])
                                  ->order('order_key asc')
                                  ->select();
        $res = get_bykey_array($data,config('scheme_type'),'type');
        cache('scheme',$res);                                              
        return $res;
    }
    /**
     * [auth_hook 权限拦截钩子]
     * @author   游兴祥
     * @return [type] [description]
     */
    protected function auth_hook($path)
    {
	$auth = config('auth');
       if (in_array($path, $auth)) {
            check_user_login();
       }
    }
}
