<?php
namespace app\index\controller;
use think\Controller;
use app\admin\model\Nav as nav_m;
class Index extends Base
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index()
    {
        $news = db('news')->where(['state'=>['eq',1],'typeid'=>['neq',40],'flag'=>['like','%a%']])->order('update_time desc')->select();
        $this->assign('news',$news);
    	$this->setPageInfo(
    			Lang('home'),
	    		$this->config_field['keywords']['v'],
	    		$this->config_field['description']['v'],
                ['public','swiper.min','page','page02']
    		);
        if (!empty(input('get.gclid'))) {
            cache(request('get')->path(),$this->fetch());
        } else {
            cache(request('get')->url(),$this->fetch());
        }
    	return $this->fetch();
    }
    /**
     * [auth_online_shop 授权网店]
     * @return [type] [description]
     */
    public function auth_online_shop()
    {
        if (!empty(input('get.search'))) {
            $where['title'] = ['like',"%".trim(input('get.search'))."%"];
        }
        $where['state'] = ['eq',1];
        $data = db('online_store')->where($where)->order('order_key asc')->select();
        $this->assign('odata',$data);
        $this->setPageInfo(
            lang('auth_online_shop'),
            $this->config_field['keywords']['v'],
            $this->config_field['description']['v'],
            ['public','swiper.min','page02']
        );
        return $this->fetch();
    }
    /**
     * [get_byname_search ajax 获取查询]
     * @return [type] [description]
     */
    public function get_byname_search()
    {
        $search = input('post.search');
        $data = db('online_store')->where(['state'=>['eq',1],'title'=>['like',"%".$search."%"]])->order('order_key asc')->select();
        $this->assign('odata',$data);
        return $this->fetch();
    }
    /**
     * [legal_statement 法律声明]
     * @return [type] [description]
     */
    public function legal_statement()
    {
        $this->setPageInfo(
                lang('legal_statement'),
                $this->config_field['keywords']['v'],
                $this->config_field['description']['v']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    /**
     * [privacy_protection 隐私保护]
     * @return [type] [description]
     */
    public function privacy_protection()
    {
        $this->setPageInfo(
                lang('privacy_protection'),
                $this->config_field['keywords']['v'],
                $this->config_field['description']['v']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    /**
     * [registration_protocol 注册协议]
     * @return [type] [description]
     */
    public function registration_protocol()
    {
        $this->setPageInfo(
                lang('registration_protocol'),
                $this->config_field['keywords']['v'],
                $this->config_field['description']['v']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    //站内搜索
    public function search()
    {
        $data = [];
        $keywords = trim(input('get.keywords'));
        //获取产品数量
        $productNum = db('product')->where(['state'=>['eq',1],'name'=>['like','%'.$keywords.'%']])->count();              //产品
        $productNum && $data['product'] = ['id'=> 1,'type'=>'Product','num'=>$productNum];

        //获取新闻数量
        $newsNum = db('news')->where(['state'=>['eq',1],'title'=>['like','%'.$keywords.'%']])->count();                   //新闻
        $newsNum && $data['news'] = ['id'=> 2,'type'=>'News','num'=>$newsNum];

        //获取解决方案数量
        $schemeNum = db('scheme')->where(['state'=>['eq',1],'name'=>['like','%'.$keywords.'%']])->count();                //解决方案
        $schemeNum && $data['scheme'] = ['id'=> 3,'type'=>'Solution','num'=>$schemeNum];

        //获取案例数量
        $casesNum = db('cases')->where(['state'=>['eq',1],'name'=>['like','%'.$keywords.'%']])->count();                  //案例
        $casesNum && $data['cases'] = ['id'=> 4,'type'=>'Cases','num'=>$casesNum];

        //获取下载资料数量
        $fileNum = db('file')->where(['state'=>['eq',1],'title'=>['like','%'.$keywords.'%']])->count();                   //下载资料
        $fileNum && $data['file'] = ['id'=> 5,'type'=>'Download','num'=>$fileNum];

        //获取常见问题数量
        $zkapi = new \expand\Zkapi;
        $response = json_decode($zkapi->get_faq_lst(['keyValue'=>$keywords,'curPage'=>1,'pageSize'=>10]),true);
        $faqNum = !empty($response['payload']['results']['dataList'][0]['allRow'])?$response['payload']['results']['dataList'][0]['allRow']:0;
        $faqNum && $data['faq'] = ['id'=> 6,'type'=>'FAQ','num'=>$faqNum];

        $sumNum = intval($productNum) + intval($newsNum) + intval($schemeNum) + intval($casesNum) + intval($fileNum) + intval($faqNum);
        $this->assign('sumNum',$sumNum);
        $this->assign('data',$data);
        $this->setPageInfo(
                'Search',
                $this->config_field['keywords']['v'],
                $this->config_field['description']['v'],
                ['public','swiper.min','page','page02']
            );

        return $this->fetch();
    }
    //ajax 站内搜索
    public function get_bykeyword_search()
    {
        $checkeds = json_decode(input('post.checkeds'));
        $keywords = trim(input('post.keywords'));
        // $checkeds = [1];
        // $keywords = '';
        $data = [];
        foreach ($checkeds as $key => $value) {
            if ($value == 1) {
                $where = ['state'=>['eq',1],'name'=>['like','%'.$keywords.'%']];
                $data[1] = db('product')->field('name as n,id,img,create_time as time,product_des as des,url_title')->where($where)->order('order_key asc')->select();
            } else if ($value == 2) {
                $where = ['state'=>['eq',1],'title'=>['like','%'.$keywords.'%']];
                $data[2] = db('news')->field('title as n,id,img,update_time as time,abstract as des,url_title')->where($where)->order('id desc')->select();
            } else if ($value == 3) {
                $where = ['state'=>['eq',1],'name'=>['like','%'.$keywords.'%']];
                $data[3] = db('scheme')->field('name as n,id,scheme_img as img,create_time as time')->where($where)->order('order_key asc')->select();
            } else if ($value == 4) {
                $where = ['state'=>['eq',1],'name'=>['like','%'.$keywords.'%']];
                $data[4] = db('cases')->field('name as n,id,img_nav as img,create_time as time')->where($where)->order('order_key asc')->select();
            } else if ($value == 5) {
                $where = ['state'=>['eq',1],'title'=>['like','%'.$keywords.'%']];
                $data[5] = db('file')->field('title as n,id,img,update_time as time,abstract as des')->where($where)->order('order_key asc')->select();
            } else if ($value == 6) {
                $zkapi = new \expand\Zkapi;
                $response = json_decode($zkapi->get_faq_lst(['keyValue'=>$keywords,'curPage'=>0,'pageSize'=>1000]),true);
                $lst = $response['payload']['results']['dataList'];
                $data[6] = $lst;
            }
        }
        $data_ = [];
        foreach ($data as $key => $value) {
            if ($key == 6) {
                $value3 = [];
                foreach ($value as $key2 => $value2) {
                    $value3['n'] = $value2['title'];
                    $value3['id'] = $value2['id'];
                    $value3['des'] = $value2['faqCateory'];
                    $value3['time'] = substr($value2['createTime'],0,10);
                    $value3['url'] = url('/zk_faq');
                    $data_[] = $value3;
                }
            } else {
                foreach ($value as $key2 => $value2) {
                    if ($key == 1) {
                        if ($value2['url_title']) {
                            $value2['id'] = $value2['url_title'];
                        }
                        $value2['url'] = url('/product_detail/'.$value2['id']);
                        $value2['img'] = '<div class="img"><b class="tran500" style="background-image: url('.$value2['img'].');"></b></div>';
                    } else if ($key == 2) {
                        if ($value2['url_title']) {
                            $value2['id'] = $value2['url_title'];
                        }
                        $value2['url'] = url('/news_detail/'.$value2['id']);
                        $value2['img'] = '<div class="img"><b class="tran500" style="background-image: url('.$value2['img'].');"></b></div>';
                    } else if ($key == 3) {
                        $value2['url'] = url('/scheme_detail/'.$value2['id']);
                        $value2['img'] = '<div class="img"><b class="tran500" style="background-image: url('.$value2['img'].');"></b></div>';
                    } else if ($key == 4) {
                        $value2['url'] = url('/cases_detail/'.$value2['id']);
                        $value2['img'] = '<div class="img"><b class="tran500" style="background-image: url('.$value2['img'].');"></b></div>';
                    } else if ($key == 5) {
                        $value2['url'] = url('Service/load?id='.$value2['id']."&callback=search");
                       // $value2['url'] = url('/login/'.$value2['id']);
                        $value2['img'] = '<div class="img"><img src="'.$value2['img'].'" class="tran300" alt="'.$value2['n'].'"></div>';
                    }
                    $value2['time'] = date("Y-m-d",$value2['time']);
                    $data_[] = $value2;
                }
            }
        }
        $page = empty(input('post.page'))?1:intval(input('post.page'));
        $lst = array_slice($data_,($page-1)*10,10);
        $res = new \expand\Zkpageajax($lst,10,$page,count($data_));
        $this->assign('res',$res);
        return $this->fetch();

    }
    public function web_site_map()
    {
        $model = new nav_m;
        $data_1 = $model->getTree('order_key asc',['show_position' => ['neq','4']]);
        $this->assign('nav_all',$data_1);
        $this->setPageInfo(
                lang('web_site_map'),
                $this->config_field['keywords']['v'],
                $this->config_field['description']['v']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    public function testyxx()
    {
      dd(json_decode(cookie('user_info'),true)); 
    }
	
    public function VLFR() {
 	$this->setPageInfo(
            'VLFR',
            'zkteco',
            'vlrf'
        );
	return $this->fetch();
    }
  
    public function showLogs() {
	header('Content-Type: text/html; charset=utf-8'); 
	$res = file_get_contents('/var/www/html/zken2/public/download.log');
	die($res);
   } 
}
