<?php
namespace app\index\controller;
use think\Controller;
use app\admin\model\Category as cat;
use app\admin\model\Product as Product_m;
use expand\Zkapi;
use think\validate;
use think\Db;
class Product extends Base
{
    /**
     * [Product_category 产品分类列表]
     */
    public function product_category($id = 0)
    {
        $c = new cat;
        $p = new Product_m; 
        $dataCate = $this->product_cat;             //产品分类
        $banner = db('category_banner')->where(['type'=>['eq','product'],'cat_id'=>['eq',$id]])->value('img');
        $cateData = get_bykey_find($dataCate,'id',$id);
        if ($id == 0) {
            $cateData['name'] = lang('product_category');
            $cateData['seo_keyword'] = $this->config_field['keywords']['v'];
            $cateData['seo_des'] = $this->config_field['description']['v'];
        } else if ($id == -1) {             //主栏目搜索 不缓存处理
            $id = 0;                        //-1 只是搜索的标记
            $flag = 'nocache';
            $cateData['name'] = lang('product_category');
            $cateData['seo_keyword'] = $this->config_field['keywords']['v'];
            $cateData['seo_des'] = $this->config_field['description']['v'];
            $search = trim(input('get.search'));
            $where['name'] = ['like','%'.$search.'%'];
        } else {    
            $category_id = $c->getChildren($id);        //获取当前分类下的所有子元素          
            $category_id[] = $id;                       //本身也算进去
            $pid = db('product_category')->where(['category_id'=>['in',$category_id]])->column('product_id');       //取出在这些分类下的商品       
            $where['id'] = ['in',$pid];
        }
        $crumb = $c->getParents($id);      //面包屑元素
        foreach ($crumb as $ckey => $cvalue) {
            $crumb[$ckey]['child'] = db('category')->where(['state'=>['eq',1],'id'=>['neq',$cvalue['id']],'parent_id'=>['eq',$cvalue['parent_id']],'type'=>['eq','product']])->order('order_key asc')->select(); 
        }
        $crumbHtml = get_crumb(lang('product_center'),url('/product_category','',false),$crumb);                 //获取去面包屑
        $menu_on = get_array_value($crumb,'id',$id);      //跳转菜单栏展开
        $where['state'] = 1;
        $order = 'order_key asc';
        $page =  !empty($cateData['page'])?$cateData['page']:9;
        $product = $p->field('name,cart_url,id,img,flag,url_title,cart_url2,cart_url3')->where($where)->order($order)->paginate($page, false, page_param());      //获取分类的产品
        $this->assign('crumbHtml',$crumbHtml);
        $this->assign('banner',$banner);
        $this->assign('menu_on',$menu_on);
        $this->assign('product',$product);
        $this->assign('cid',$id);
        $this->setPageInfo(
                $cateData['name'],
                $cateData['seo_keyword'],
                $cateData['seo_des'],
                ['public','swiper.min','page','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        if(empty($flag)) cache(request('get')->url(),$this->fetch());                     
        return $this->fetch();
    }
    /**
     * [detail 商品详细]
     * @param  string $id [description]
     * @return [type]     [description]
     */
    public function detail($id = '')
    {
        if ($id) {                    
            $c = new cat;
            $p = new Product_m;
            $where['state'] = ['eq',1];           
            if ($id > 0) {                
                $where['id'] = ['eq',$id];
            } else {
                $where['url_title'] = ['eq',$id];
            }
            $data = $p->where($where)->find();
            if (count($data)>0) {
                $cid = db('product_category')->where(['product_id'=>['eq',$data['id']]])->value('category_id');
                $crumb = $c->getParents($cid);
                foreach ($crumb as $ckey => $cvalue) {
                    $crumb[$ckey]['child'] = db('category')->where(['state'=>['eq',1],'id'=>['neq',$cvalue['id']],'parent_id'=>['eq',$cvalue['parent_id']],'type'=>['eq','product']])->order('order_key asc')->select(); 
                }
                $crumbHtml = get_crumb_product(lang('home'),url('/index'),$crumb,$data['name']);                 //获取产品面包屑
               
                $this->assign('detailData',$data);
                $this -> assign("content_list",$data['product_trait']['content']);
                $this->assign('crumbHtml',$crumbHtml);
                $this->setPageInfo(
                    $data['name'],
                    $data['seo_keyword'], 
                    $data['seo_des'],
                    ['public','swiper.min','page02'],
                    ['jquery.min','swiper.min','page','layui','layer/layer']
                    );
                cache(request('get')->url(),$this->fetch());
                return $this->fetch();
            }         
        }
        abort(404,'查无记录');
    }
    /**
     * [get_byname_search 商品分类通过名称精确查找]
     * @return [type] [description]
     */
    public function get_byname_search()
    {
        $name = trim(input('post.search'));
        $cid = trim(input('post.cid'));
        $where['name'] = ['like','%'.$name.'%'];
        $p = new Product_m;
        $page = 9;
        $data = $p->field('name,id,cart_url,img,flag,url_title,cart_url2,cart_url3')->where($where)->order('order_key asc')->paginate($page, false, page_param()); 
        $this->assign('getData',$data);
        $this->assign('cid',$cid);
        return $this->fetch();
    }
    /**
     * add_order_cookie 增加到cookie
     */
    public function add_order_cookie()
    {
        $id = input('post.pid');
        $flag = empty(input('post.flag'))?'':input('post.flag');
        $idnum = empty(input('post.idnum'))?$id.'-1':input('post.idnum');
        if (empty(session('user_flag'))) return ajaxReturn(lang('please_login_err'));
        if ($id) {
            cookie_add_order($id,$idnum,$flag);
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('ajax_err'));
        }   
    }
    /**
     * [del_order_cookie 删除cookie]
     * @return [type] [description]
     */
    public function del_order_cookie()
    {
        $ids = json_decode(input('post.ids'),true);      //要删除数组id 合集
        $user_id = json_decode(cookie('user_info'))->id;   
        $falg = $user_id.'-join_inquiry';           //用户ID+-join_inquiry 存储购物车
        $cart = json_decode(cookie($falg),true);             
        $pids = $cart['pids'];                          //原生数组
        $idnums = $cart['idnums'];
        if (count($ids)>0 && count($pids)>0) {
            foreach ($pids as $key => $value) {
                if(in_array($value, $ids)){
                    $cart['sum_num'] = $cart['sum_num']-$idnums[$value];
                    unset($pids[$key]);
                    unset($idnums[$value]);
                }
            }
            $cart['pids'] = $pids;
            $cart['idnums'] = $idnums;
            cookie($falg,json_encode($cart));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_error'));
        }
    }
    /**
     * [del_order_cookie_2 第二步删除cookie 保留订单]
     * @return [type] [description]
     */
    public function del_order_cookie_2()
    {
        $k = input('post.key');                 //要删除数组id 合集
        $user_id = json_decode(cookie('user_info'))->id; 
        $falg = $user_id.'-order_step2';                     //获取存入的第二步信息
        $data = json_decode(cookie($falg),true);                
        $info = json_decode($data['info'],true);                
        if (count($info)>0) {
            foreach ($info as $key => $value) {
                if($key = $k)unset($info[$key]);
            }
            $data['info'] = json_encode($info);
            cookie($falg,json_encode($data));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_error'));
        }
    }
    /**
     * [order_step1 订单第一步]
     * @return [type] [description]
     */
    public function order_step1()
    {
        if (intval(trim(input('id')))) {
            cookie_add_order(intval(trim(input('id'))),intval(trim(input('id'))).'-1','+');    //立即询价把本身加入询价
            $this->redirect('/order_step1');                //防止刷新自带ID
        }
        $user_id = json_decode(cookie('user_info'))->id;   
        $falg = $user_id.'-join_inquiry';               //用户ID+-join_inquiry 存储购物车
        $cart = json_decode(cookie($falg),true);
        $pids = $cart['pids'];
        $idnums = $cart['idnums'];
        $order_number = $cart['order'];//订单号
        $productData = db('product')->field('id,name,product_des,img,url_title')->where(['state'=>['eq',1],'id'=>['in',$pids]])->select();
        $this->assign('productData',$productData);
        $this->assign('order_number',$order_number);
        $this->assign('idnums',$idnums);
        $this->setPageInfo(
            lang('order_step1'),
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','page'],
            ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        return $this->fetch();  
    }
    //获取公用购物车
    public function get_cart_html()
    {
        if (empty(session('user_flag'))) {
            return;
        } else {
            $user_id = json_decode(cookie('user_info'))->id;  
            $falg = $user_id.'-join_inquiry';               //用户ID+-join_inquiry 存储购物车
            $cart = json_decode(cookie($falg),true);
            $pids = $cart['pids'];
            $idnums = $cart['idnums'];
            $order_number = $cart['order'];//订单号
            $productData = db('product')->field('id,name,product_des,img,url_title')->where(['state'=>['eq',1],'id'=>['in',$pids]])->select();
            $this->assign('productData',$productData);
            $this->assign('order_number',$order_number);
            $this->assign('idnums',$idnums);
            return $this->fetch();
        }
    }
    /**
     * [order_step1_change 订单第一步进行删除 数量等操作  第一步确认]
     * @return [type] [description]
     */
    public function order_step1_change()
    {
        if (empty(session('user_flag'))) return ajaxReturn(lang('please_login_err'));  //ajax 防止没有登录
        $order_number = input('post.order_number');
        $info = input('post.info');
        if (count($info)>0 && !empty($order_number)) {
            $user_id = json_decode(cookie('user_info'))->id;   
            // $falg = $user_id.'-join_inquiry';               //用户ID+-join_inquiry 存储购物车
            // cookie($falg,null);                             //清空询价单第一步信息
            $falg2 = $user_id.'-order_step2';                //第二步信息存入到cookies
            $arr['order_number'] = trim($order_number);
            $arr['info'] = $info;
            cookie($falg2,json_encode($arr));
            return ajaxReturn(lang('action_success'),1); 
        } else {
            return ajaxReturn(lang('ajax_err'));
        }
    }
    /** 
     * [order_step2 订单提交第二步]
     * @return [type] [description]
     */
    public function order_step2()
    {
        $user_id = json_decode(cookie('user_info'))->id; 
        $falg = $user_id.'-order_step2';                     //获取存入的第二步信息
        $data = json_decode(cookie($falg),true);             //判断第一步有没有数据进来 没有回到第一步
        $z = new Zkapi;                                    
        if (empty($data)) {
            $this->redirect('/order_step1');
            exit;
        } else {
            if (request()->isPost()) {
                $orderData = input('post.');
                cookie('input_order2',$orderData);
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
                if (captcha_check($orderData['verify']) != 1) {                                            //检验验证码
                    cookie('order_commit_err',json_encode(lang('code_error')));
                    $this->redirect('/order_step2');
                    exit;
                }
                if (!$validate->check($orderData)) {
                    cookie('order_commit_err',json_encode(lang('lack_of_necessary_information')));
                    $this->redirect('/order_step2');
                    exit;
                } else {
                    $orderData['address'] = empty($orderData['city'])?$orderData['country']:$orderData['country'].','.$orderData['city'];
                    unset($orderData['province']);unset($orderData['city']);unset($orderData['verify']);
                    $response = json_decode($z->apply_inquiry($orderData,session('user_flag')));
                    if ($response->code == '00000000') {
                        $falg2 = $user_id.'-join_inquiry';               //用户ID+-join_inquiry 存储购物车
                        cookie($falg2,null);                             //清空询价单第一步信息
                        cookie($falg,null);               //情空
                        cookie('input_order2',null);      //提交信息清空
                        $this->redirect('index/Product/order_finish');
                        exit;
                    } else {
                        cookie('order_commit_err',json_encode($response->msg));
                        $this->redirect('/order_step2');
                        exit;
                    }  
                }
            }
            $order_number = $data['order_number'];
            $info = json_decode($data['info'],true);            //产品信息
            $response = json_decode($z->get_country(),true);  
            $country = $response['payload']['results']['dataList'];
            $this->assign('country',$country);
            $this->assign('order_number',$order_number);
            $this->assign('info',$info);
            if (empty(cookie('input_order2'))) {                    //第一次获取个人信息
                cookie('input_order2',json_decode(cookie('user_info'),true));            //获取用户信息
            } 
            $this->setPageInfo(
                lang('order_step1'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
                );
            return $this->fetch(); 
        }           
    }
    /**
     * [order_finish 订单提交完成]
     * @return [type] [description]
     */
    public function order_finish()
    {
        $this->setPageInfo(
            lang('order_finish'),
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','page'],
            ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        return $this->fetch(); 
    }
    public function download_public_key() {
	$h = $_SERVER['HTTP_REFERER'];
	if ($h == "https://www.zkteco.com/en/login?redirect_url=https://www.zkteco.com/en/download_public_key") {
		header("Location: https://www.zkteco.com/en/Index/Service/license_active");
		exit;
		//header("Location:https://www.zkteco.com/en/product_detail/ZKAccess3.5.html");
	}
	else {
	$id = 994;
	$user = json_decode(cookie('user_info'),true);
        $user_id = trim($user['id']);            //获取用户ID
	$country = trim($user['country']);
	$data = ['file_id'=>$id,'user_id'=>$user_id,'country'=>$country];
	$fh = new \app\admin\model\DownloadHistory;
        $fh->save($data);
	$http = new \expand\Http;
	$http::download('/var/www/html/zken2/public/static/home/images/PleaseCopyPublicKeyAndActivate.txt', 'PleaseCopyPublicKeyAndActivate.txt');
        }
   }
}
