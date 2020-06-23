<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use expand\Http;
use expand\Zkapi;
use app\admin\model\DownloadHistory;
use think\Validate;
class Service extends Base
{
    public function _initialize(){
        parent::_initialize();
        $this->zkapi = new Zkapi;
    }
    /**
     * [category 下载分类]
     * @param  string $id [分类的ID]
     */
    public function category($id = 0)
    {   
        $cateData = get_bykey_find($this->file_cat,'id',$id);
        if ($id == 0){
            $cateData['name'] = lang('download_center');
            $cateData['seo_keyword'] = $this->config_field['keywords']['v'];
            $cateData['seo_des'] = $this->config_field['description']['v'];
        }
    	$this->setPageInfo(
                $cateData['name'],
                $cateData['seo_keyword'],
                $cateData['seo_des']
            );
        $this->assign('cid',$id);
        cache(request('get')->url(),$this->fetch());   
        return $this->fetch();
    }
    /**
     * [get_category_list 分类列表数据]
     */
    public function get_category_list()
    {
        $cid = input('cid');
        $search = trim(input('post.search'));           //搜索
        if (!empty($search)) {
            $where['title|abstract'] = ['like', '%'.$search.'%']; 
        } 
        $firstRow = intval(trim(input('post.time')));
        $cateData = get_bykey_find($this->file_cat,'id',$cid);
        if (isset($firstRow)) {
            if(!$cid)$cateData['page'] = 10;
            $firstRow = $firstRow*$cateData['page'];
            $listRows = $cateData['page'];
            $where['state'] = ['eq',1]; 
            if($cid)$where['cate_id'] = ['eq',$cid]; 
            $data = Db::name('file')->where($where)
                                    ->order('order_key asc ,update_time desc')
                                    ->limit($firstRow,$listRows)
                                    ->select(); 
            $this->assign('file_cate_data',$data);
            return $this->fetch();
        }
    }
    /**
     * [load 分类下载]
     */
    public function load()
    {
        $id = input('id');  //下载的id
        $file_info = Db::name('file')->field('file_url,cate_id,file_name')->where('id',$id)->find();
        $file = $file_info['file_url'];
        $cat = $file_info['cate_id'];
        $callback = input("callback");
        $auth = db('category_download')->where(['category_id'=>['eq',$cat]])->column('user_type');  //判断有没有权限
        if (count($auth)>0) {
            if (!empty(session('user_flag'))&&!empty(cookie('user_info'))) {
                if ($id) {
                    $user = json_decode(cookie('user_info'),true);
                    $user_id = trim($user['id']);            //获取用户ID
                    if (in_array($user['userType'],$auth)) {  
                        if (file_exists('.'.$file)) {
                            $filename = '.'.$file;
                            $showname = !empty($file_info['file_name'])?urlencode($file_info['file_name']):substr($file,24);
                            $data = ['file_id'=>$id,'user_id'=>$user_id,'country'=>$user['country']];
                            $fh = new DownloadHistory;
                            $fh->save($data);
                            $http = new Http();
                            $http::download($filename, $showname);
                        }
                    } else {
                        return $this->error(lang('no_auth_load'));//没有权限
                    }
                }
                return $this->error(lang('illegal_operation')); //非法操作
            }
            if(empty($callback)){
                $jump_url = url("/login");
                $this->redirect($jump_url);
              //  Header("Location:http://{$_SERVER['SERVER_NAME']}/login ");
            }else{
                $jump_url = url("/login?redirect_url=http://{$_SERVER['SERVER_NAME']}/{$callback}");
                $this->redirect($jump_url);
               // Header("Location:http://{$_SERVER['SERVER_NAME']}/login?redirect_url=http://{$_SERVER['SERVER_NAME']}/{$callback} ");
            }

            exit;
           // return $this->error(lang('please_login_err'),'/login');   //非法操作
        } else {
            $id = input('id');  //下载的id
            if ($id) {
                if (file_exists('.'.$file)) {
                    $filename = '.'.$file;
                    $showname = !empty($file_info['file_name'])?urlencode($file_info['file_name']):substr($file,24);
                    if (!empty(session('user_flag'))) {
                        $user = json_decode(cookie('user_info'),true);
                        $user_id = trim($user['id']); 
                        $data = ['file_id'=>$id,'user_id'=>$user_id];
                        $fh = new DownloadHistory;
                        $fh->save($data);
                    }
                    $http = new Http();
                    $http::download($filename, $showname);
                }
            }
            return $this->error(lang('illegal_operation')); //非法操作
        }
    }
    /**
     * [download_history 下载记录]
     * @return [type] [description]
     */
    public function download_history()
    {
        $this->setPageInfo(
            lang('download_history'),  
            $this->config_field['keywords']['v'], 
            $this->config_field['description']['v'],
            ['public','swiper.min','page','account','join','center','page02']
        );
        return $this->fetch();
    }
    /**
     * [get_fh_list 下载历史记录]
     * @return [type] [description]
     */
    public function get_fh_list()
    {
        if (empty(session('user_flag'))) return lang('please_login_err');
        $firstRow = intval(trim(input('post.time')));
        $user = json_decode(cookie('user_info'),true);
        $user_id = trim($user['id']);
        $search = trim(input('post.search'));           //搜索
        if (!empty($search)) {
            $ids = db('file')->where(['title'=>['like',"%".$search."%"]])->order('order_key asc')->column('id');
            $where['file_id'] = ['in',$ids];
        } 
        if (isset($firstRow)) {
            $pageSize = intval(10*$firstRow);
            $listRows = 10;
            $where['user_id'] = ['eq',$user_id]; 
            $fh = new DownloadHistory;
            $data = $fh->where($where)
                        ->order('id desc')
                        ->limit($pageSize,$listRows)
                        ->select(); 
            $this->assign('fhData',$data);
            return $this->fetch();
        }
    }
    /**
     * 产品保修
     */
    public function product_warranty()
    {
        $this->setPageInfo(
                lang('product_warranty'),
                lang('pw_keyword'),
                lang('pw_des')
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    /**
     * [product_warranty_load 产品保修正常下载]
     */
    public function product_warranty_load()
    {
        $path = substr($this->ad['产品保修政策'][0]['video'],3);
        if (file_exists('.'.$path)) {
            $showname = !empty($this->ad['产品保修政策'][0]['video_name'])?urlencode($this->ad['产品保修政策'][0]['video_name']):substr($this->ad['产品保修政策'][0]['video'],24);
            $filename = $path;
            $http = new Http();
            $http::download($filename, $showname);
        }
    }
    /**
     * [security_check 防伪查询]
     */
    public function security_check()
    {
        $this->setPageInfo(
                lang('service_tile_sn'),
                lang('sn_keyword'),
                lang('sn_des'),
                ['public','swiper.min','page','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
  
    /**
     * [repair_register_step2 维修登记]
     * @return [type] [description]
     */
    public function repair_register_step2()
    {
        $this->setPageInfo(
                lang('repair_register'),   
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','account','join','center','page02']
            );
        return $this->fetch();
    }

    public function repair_commit()
    {
        if (empty(session('user_flag'))) return ajaxReturn(lang('please_login_err'));
        $info = json_decode(input('post.info'),true);                //客户信息
        $pinfo = json_decode(input('post.pinfo'));                   //产品信息
        $verify = input('post.verify');                              //验证码
        if (!captcha_check($verify)) {                               //检验验证码
            return ajaxReturn(lang('code_error'));
            exit;
        } else {
            $value['customerName']  = RemoveXSS($info[0]);                         //公司名称
            $value['contact']  = RemoveXSS($info[1]);                              //客户联系人    
            $value['email']  = RemoveXSS($info[2]);                                //邮箱       
            $value['tel']  = RemoveXSS($info[3]);                                  //联系电话
            $value['address']  = RemoveXSS($info[4]);                              //联系地址
            if (count($pinfo)>0) {
                foreach ($pinfo as $pk=>$pv) {
                    $value_v = [];
                    $value_v['machineModel'] = RemoveXSS($pv[0]);
                    $value_v['sn'] = RemoveXSS($pv[1]);
                    $value_v['malfunctionDesc'] = RemoveXSS($pv[2]);
                    $value['repairDetailList'][] = $value_v;
                }
            }
            $response = json_decode($this->zkapi->repair_save($value,session('user_flag')));
            if ($response->code == '00000000') {                                //获取中控验证结果
                cache(session('user_flag').'_retun',json_encode($response->payload->results->repairInfo));
                return ajaxReturn(lang('action_success'),1);                    
            } else {
                return ajaxReturn($response->msg);
            }  
        }
    }
    /**
     * [repair_finish 提交完成]
     * @return [type] [description]
     */
    public function repair_finish()
    {
        $response = cache(session('user_flag').'_retun');
        cache(session('user_flag').'_retun',null);
        $this->assign('response',$response);
        $this->setPageInfo(
                lang('repair_finish'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
        return $this->fetch();
    }
    /**
     * [faq 常见问题]
     */
    public function zk_faq()
    {
        $resp = json_decode($this->zkapi->get_productCategory(),true);                          //产品分类
        $resn = json_decode($this->zkapi->get_productName(),true);                              //产品名称
        $resm = json_decode($this->zkapi->get_productModel(),true);                             //产品模型

        $product_cat = empty($resp['payload']['results']['dataList'])?'':$resp['payload']['results']['dataList'];
        $product_name = empty($resp['payload']['results']['dataList'])?'':$resn['payload']['results']['dataList'];
        $product_model = empty($resp['payload']['results']['dataList'])?'':$resm['payload']['results']['dataList'];

        $response = json_decode($this->zkapi->get_faqcategory(''));
        $faqcategory = empty($response->payload->results->category)?"":$response->payload->results->category;
        $this->assign('productCat',$product_cat);
        $this->assign('productName',$product_name);
        $this->assign('productModel',$product_model);
        $this->assign('faqcategory',$faqcategory);                                     //获取分类
        $this->setPageInfo(
                lang('zk_faq'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','account','join','center','page02']
            );
        return $this->fetch();
    }
    /**
     * [get_faq_lst 获取常见问题列表]
     */
    public function get_faq_lst()
    {
        $data['category'] = trim(input('post.cid'));                         //分类ID
        $data['productCategory'] = trim(input('post.productCategory'));      //产品类别
        $data['productName'] = trim(input('post.productName'));              //产品名称 
        $data['productModel'] = trim(input('post.productModel'));            //产品型号  
        $data['keyValue'] = trim(input('post.keyValue'));                    //关键字
        $data['curPage'] =  intval(input('post.time'));                      //当前页
        $data['pageSize'] = intval(config('paginate')['list_rows']);                             //显示数量
        $response = json_decode($this->zkapi->get_faq_lst($data),true);  
        if (!empty($response)) {   
            if ($response['code'] == '00000000') {   
                if (!empty($response['payload']['results']['dataList'])) {
                    $lst = $response['payload']['results']['dataList'];  
                    $total = empty(intval($lst[0]['allRow']))?0:intval($lst[0]['allRow']);                           //获取中控验证结果
                    $res = new \expand\Zkpageajax($lst,$data['pageSize'],$data['curPage'],$total);
                    $this->assign('lst',$res);
                    return $this->fetch();
                }
            } else {
                return $response['msg'];
            }
        } 
    }
    /**
     * [reservation 服务支持首页]
     * @return [type] [description]
     */
    public function index()
    {
        $this->setPageInfo(
                lang('service'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02','page']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    /**
     * [reservation 预约上门服务]
     * @return [type] [description]
     */
    public function reservation()
    {
        $this->setPageInfo(
                lang('reservation'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','account','join','center']
            );
        return $this->fetch();
    }

    /**
     * [reservation_commit 预约上门服务]
     * @return [type] [description]
     */
    public function reservation_commit()
    {
        if (empty(session('user_flag'))) return ajaxReturn(lang('please_login_err'));
        $info = json_decode(input('post.info'),true);                     //客户信息
        $verify = input('post.verify');

        if (!captcha_check($verify)) {                               //检验验证码
            return ajaxReturn(lang('code_error'));
            exit;
        } else {
            $value['companyName']  = RemoveXSS($info[0]);                              //公司名称
            $value['contactName']  = RemoveXSS($info[1]);                              //客户联系人    
            $value['email']  = RemoveXSS($info[2]);                                     //邮箱       
            $value['tel']  = RemoveXSS($info[3]);                                      //联系电话
            $value['address']  = RemoveXSS($info[4]);                                  //联系地址
            $value['product'] = RemoveXSS(trim(input('post.product')));                //产品设备
            $value['serialNumber'] = RemoveXSS(trim(input('post.serialNumber')));      //设备序列号     
            $value['problemDes'] = RemoveXSS(trim(input('post.problemDes')));          //需求描述
            $value['appointmentTime'] = RemoveXSS(trim(input('post.appointmentTime')));//增加时间
            $response = json_decode($this->zkapi->apply_reservation($value,session('user_flag')));
            if ($response->code == '00000000') {                                //获取中控验证结果
                return ajaxReturn(lang('action_success'),1);                    
            } else {
                return ajaxReturn($response->msg);
            }
        }  
    }
    /**
     * [reservation_finish 预约上面服务申请完成]
     * @return [type] [description]
     */
    public function reservation_finish()
    {
        $this->setPageInfo(
                lang('reservation_finish'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v']
            );
        return $this->fetch();
    }
    /**
     * [online_support 在线支持]
     * @return [type] [description]
     */
    public function online_support()
    {
        $this->setPageInfo(
                lang('online_support'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v']
            );
        cache(request('get')->url(),$this->fetch());
        return $this->fetch();
    }
    /**
     * [ticket_save 提交工单]
     * @return [type] [description]
     */
    public function ticket_save()
    {
        $resp = json_decode($this->zkapi->get_tproblem_category(),true);
        $problem_cat = empty($resp['payload']['results']['dataList'])?'':$resp['payload']['results']['dataList']; 
        $this->assign('problem_cat',$problem_cat);
        $this->setPageInfo(
                lang('ticket_save'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v']
            );
        return $this->fetch();
    }
    public function ticket_commit()
    {
        if (empty(session('user_flag'))) return ajaxReturn(lang('please_login_err'));
        $info = json_decode(input('post.info'),true);                     //客户信息
        $verify = input('post.verify');
        $token = input('post.token');
        if (!captcha_check($verify)) {                               //检验验证码
            return ajaxReturn(lang('code_error'));
            exit;
        } else {
            if (empty(session('__token__')) || session('__token__') !== $token) {
                return ajaxReturn('Do not repeat the submission!');    
            } else {
                session('__token__',null);
            }
            $value['customerName']  = RemoveXSS($info[0]);                             //公司名称
            $value['linkman']  = RemoveXSS($info[1]);                                  //联系人    
            $value['email']  = RemoveXSS($info[2]);                                    //邮箱       
            $value['telete']  = RemoveXSS($info[3]);                                   //联系电话
            $value['address']  = RemoveXSS($info[4]);                                  //联系地址
            $value['questionType'] = RemoveXSS(trim(input('post.questionType')));      //问题类型
            $value['questTitle'] = RemoveXSS(trim(input('questTitle')));               //问题标题     
            $value['uploadfile'] = trim(input('post.uploadfile'));                     //上传文件
            $value['content'] = RemoveXSS(trim(input('content')));                     //问题内容
            $response = json_decode($this->zkapi->ticket_save($value,session('user_flag')));
            if ($response->code == '00000000') {                                //获取中控验证结果
                return ajaxReturn(lang('action_success'),1);                    
            } else {
                return ajaxReturn($response->msg);
            }
        }  
    }
    /**
     * [ticket_finish 问题订单提交完成]
     * @return [type] [description]
     */
    public function ticket_finish()
    {
        $this->setPageInfo(
                lang('ticket_finish'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v']
            );
        return $this->fetch();
    }

    public function train_center()
    {
        $tdata = db('train_category')->where(['state'=>['eq',1]])->order('order_key asc')->select();
        $this->assign('tdata',$tdata);
        $this->setPageInfo(
                lang('train_center'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
    }
    /**
     * [video_cat 视频]
     * @param  string $id [description]
     * @return [type]     [description]
     */
    public function video_cat($id = '')
    {
        $ids = db('traincat_video')->where(['traincat_id'=>['eq',$id]])->column('video_id');
        $data = db('video')->where(['state'=>['eq',1],'id'=>['in',$ids]])->order('order_key asc')->select();
        $this->assign('data',$data);
        $this->setPageInfo(
                lang('video_cat'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
    }

    /**
     * [video_cat 视频]
     * @param  string $id [description]
     * @return [type]     [description]
     */
    public function file_cat($id = '')
    {
        $ids = db('traincat_file')->where(['traincat_id'=>['eq',$id]])->column('file_id');
        $data = db('file')->where(['state'=>['eq',1],'id'=>['in',$ids]])->order('order_key asc,update_time desc')->select();
        $this->assign('data',$data);
        $this->setPageInfo(
                lang('file_cat'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
    }

    public function technology_cat($id = '')
    {
        $data = db('train_category')->where(['id'=>['eq',$id]])->value('technology');
        $this->assign('data',$data);
        $this->setPageInfo(
                lang('technology_cat'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
    }

    public function apply_training()
    {
        if (request()->isPost()) {
            if (empty(session('user_flag'))) return ajaxReturn(lang('please_login_err'));
            $data = input('post.');
            $token = input('post.token');
            if (empty(session('__token__')) || session('__token__') !== $token) {
                return ajaxReturn('Do not repeat the submission!');    
            } else {
                session('__token__',null);
            }
            $info = [];
            foreach ($data as $key => $value) {
                if ($key != 'token')
                    $info[$key] = RemoveXSS($value);
            }
            $response = json_decode($this->zkapi->entraining_commit($info,session('user_flag')));
            if ($response->code == '00000000') { 
                return ajaxReturn(lang('action_success'),1);
            } else {
                return ajaxReturn(lang('action_error'));
            }
        }
        $response = json_decode($this->zkapi->get_country(),true);  
        $country = $response['payload']['results']['dataList'];
        $this->assign('country',$country);
        $this->setPageInfo(
                lang('apply_training'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
    }
    /*
        金牌讲师
     */
    public function teacher()
    {
        $data = db('teacher')->where(['state'=>['eq',1]])->order('order_key asc')->select();
        $this->assign('data',$data);
        $this->setPageInfo(
                lang('teacher'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
    }
    /**
     * [course_lst 课程列表]
     * @return [type] [description]
     */
    public function course_lst()
    {
         
        $tt = strtotime(date('Y-m',time()) . '-29 00:00:01');
        //下下个月
        $now_m_4 = date("n", strtotime("+2 month",$tt));    
        $now_y_4 = date("Y", strtotime("+2 month",$tt));
        $data[$now_m_4] = db('course')->query("select * from zk_course where month(c_date)='".$now_m_4."' and year(c_date)='".$now_y_4."' and state=1 order by c_date asc");
        $data[$now_m_4]  = $this -> deal_with_timezone($data[$now_m_4]);

        //下个月
        $now_m_3 = date("n", strtotime("+1 month",$tt));    
        $now_y_3 = date("Y", strtotime("+1 month",$tt));
        $data[$now_m_3] = db('course')->query("select * from zk_course where month(c_date)='".$now_m_3."' and year(c_date)='".$now_y_3."' and state=1  order by c_date asc");
        $data[$now_m_3]  = $this -> deal_with_timezone($data[$now_m_3]);
        

        $t = strtotime(date('Y-m',time()) . '-01 00:00:01');
        $now_m = date('n',$t); //当前月份
        $now_y = date('Y',$t);
        $data[$now_m] = db('course')->query("select * from zk_course where month(c_date)='".$now_m."' and year(c_date)='".$now_y."' and state=1 order by c_date asc"); //这个月
        //处理时区问题
        $data[$now_m] = $this -> deal_with_timezone($data[$now_m]);

        $now_m_1 = date("n", strtotime("-1 month",$t));
        $now_y_1 = date("Y", strtotime("-1 month",$t));
        $data[$now_m_1] = db('course')->query("select * from zk_course where month(c_date)='".$now_m_1."' and year(c_date)='".$now_y_1."' and state=1 order by c_date asc");//上过月
        //处理时区问题
        $data[$now_m_1] = $this -> deal_with_timezone($data[$now_m_1]);


        $now_m_2 = date("n", strtotime("-2 month",$t));    
        $now_y_2 = date("Y", strtotime("-2 month",$t));
        $data[$now_m_2] = db('course')->query("select * from zk_course where month(c_date)='".$now_m_2."' and year(c_date)='".$now_y_2."' and state=1 order by c_date asc");//上上个月
        //处理时区问题
        $data[$now_m_2]  = $this -> deal_with_timezone($data[$now_m_2]);

        $this->assign('data',$data);
        $this->setPageInfo(
                lang('course_lst'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
    }

    /**
     * @param $data
     * @return mixed
     * 处理时区问题
     */
    public function deal_with_timezone($data){

        if(empty($data)){
            return $data ;
        }

        foreach($data as $k => $v){
            //清除没有用的
            unset($data[$k]['time2'],$data[$k]['time3'],$data[$k]['time4'],$data[$k]['time5'],$data[$k]['time6'],$data[$k]['time7']);

            //处理时间格式  拼接开始时间 格式一2018-03-06 16:00-17:00
            if(substr_count($data[$k]['time1'],'-') > 1){
                $time_arr = explode(' ',$data[$k]['time1']) ;
                $hourse_arr = explode('-',$time_arr[1]);
            }else{
            //格式二 16:00-17:00
                $hourse_arr = explode('-',$data[$k]['time1']) ;
            }

            $start_time = $data[$k]['c_date'] . ' ' . $hourse_arr[0] .  ':00';
            $end_time = $data[$k]['c_date'] . ' ' . $hourse_arr[1];

            //以GMT+8 为基准 计算GMT-5时区时间
            $start_time_str = strtotime($start_time) - 13*60*60;
            $end_time_str = strtotime($end_time) - 13*60*60;
            //格式一2018-03-06 16:00-17:00
            if(substr_count($data[$k]['time1'],'-') > 1){
                $data[$k]['time2'] = date("Y-m-d H:i",$start_time_str) .' - ' . date("H:i",$end_time_str);
            }else{
                //格式二 16:00-17:00
                $data[$k]['time2'] = date("Y-m-d H:i",$start_time_str) .' - ' . date("H:i",$end_time_str);
                //$data[$k]['time2'] = date("H:i",$start_time_str) .' - ' . date("H:i",$end_time_str);
            }

            //GMT-6时区时间
            $start_time_str = strtotime($start_time) - 14*60*60;
            $end_time_str = strtotime($end_time) - 14*60*60;
            //格式一2018-03-06 16:00-17:00
            if(substr_count($data[$k]['time1'],'-') > 1){
                $data[$k]['time3'] = date("Y-m-d H:i",$start_time_str) .' - ' . date("H:i",$end_time_str);
            }else{
                //格式二 16:00-17:00
                $data[$k]['time3'] = date("Y-m-d H:i",$start_time_str) .' - ' . date("H:i",$end_time_str);
                //$data[$k]['time3'] = date("H:i",$start_time_str) .' - ' . date("H:i",$end_time_str);
            }

            //修改time1显示格式
            if(substr_count($data[$k]['time1'],'-') == 1){

                $data[$k]['time1'] = $data[$k]['c_date'] .' ' . $data[$k]['time1'] ;

            }

        }

        return $data ;
    }

    //课程介绍
    public function course_detail($id = 0)
    {
        if ($id) {
            $data = db('course')->where(['id'=>['eq',$id],'state'=>['eq',1]])->find();
            if ($data) {
                $this->assign('data',$data);
                $this->setPageInfo(
                    lang('course_detail'),  
                    $this->config_field['keywords']['v'], 
                    $this->config_field['description']['v'],
                    ['public','swiper.min','page02']
                );
                return $this->fetch();
            } else {
               return $this->fetch('layout/404'); 
            }

        } else {
            return $this->fetch('layout/404');
        }
    }
   
   //认证流程
   public function certificate()
   {
       //拿到关联文件
       $where = array(
           'traincat_id' => 7
       );

       $id_arr = db("traincat_file") ->field("file_id") -> where($where) -> select();
       $id_str = '';
        foreach($id_arr as $k => $v){
            $id_str .= $v['file_id'] .',';
        }
       $id_str = substr($id_str,0,-1);

        $data = db('file')->where(['state'=>['eq',1],'id'=>['in',$id_str]])->order('order_key asc')->select();

        $this->assign('down',$data);
        $this->setPageInfo(
                lang('certificate'),  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
   }
   public function license_active() {
        $this->setPageInfo(
                "License Activation",  
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
   }
	
  public function upload() {
  	$this->setPageInfo(
                "License Activation",
                $this->config_field['keywords']['v'],
                $this->config_field['description']['v'],
                ['public','swiper.min','page02']
            );
        return $this->fetch();
  }
  public function active() {
 	$aData = input('post.');
	$k = array_keys($aData);
	if (substr($k[0],-6) != 'SN_xml' && substr($k[1],-7) !='upk_xml') {
	   return ajaxReturn('UPK file and SN file can not match.'); 
	}
	foreach ($aData as $key => $value) {
	      $value = substr($value,3);
	      $oXml  = simplexml_load_file(".".$value);
	      $oXml->id = trim(str_replace(PHP_EOL, '', $oXml->id));
	      if (!empty($oXml->company)) {
	     	  $aXml['upk'] = json_decode(json_encode($oXml),true); 
              } else {
	      	  $aXml['sn'] = json_decode(json_encode($oXml),true);
	      }
	}
	if ($aXml['upk']['id'] == $aXml['sn']['id']) {
	   $data = $aXml['upk'];
	   $data['company']['phone_']       = is_array($data['company']['phone_'])?'':$data['company']['phone_'];
           $data['company']['phonePrefix_'] =  is_array($data['company']['phonePrefix_'])?'':$data['company']['phonePrefix_'];
           $data['company']['phoneSuffix_'] = is_array($data['company']['phoneSuffix_'])?'':$data['company']['phoneSuffix_'];
           $data['company']['phone']        = is_array($data['company']['phone'])?'':$data['company']['phone'];
	   $data['license']                 = $data['license']['module'];
	   
	   
	   if (!empty($data['license'][0]['id'])) {
           	$data['license']                 = $data['license'];
           } else {
		$data['license']                 = [$data['license']];
	   }
	   if (is_array($data['license'])) {
		foreach($data['license'] as $k=>$v) {
		    $data['license'][$k]['serialNumber'] = is_array($data['license'][$k]['serialNumber'])?"":$data['license'][$k]['serialNumber'];
		}
	   }
           $data = json_encode($data);
           $xmlinfo['xmlInfo'] = $data;
	   $xmlinfo['apl']     = 'zkt';
	   //dd($data);
	   file_put_contents('download.log','激活接口参数:'.$data."\n",FILE_APPEND);
	   $res = $this->curl_post("https://59.188.29.77:8443/ZKBioLicense/authController.do?websiteActiveAuth",http_build_query($xmlinfo));
           file_put_contents('download.log','激活接口返回结果:'.$res."\n",FILE_APPEND);
	   $res = json_decode($res,true);
	   //file_put_contents('download.log','激活接口返回结果:'.json_encode($res)."\n",FILE_APPEND);
	   if (isset($res['error']) && empty($res['error'])) {
		$rData = ['license'=>$res['license'],'id'=>$aXml['sn']['id']];
		$user = json_decode(cookie('user_info'),true);
                $user_id = trim($user['id']);            //获取用户ID
                $country = trim($user['country']);
		$data = ['file_id'=>998,'user_id'=>$user_id,'country'=>$country];
        	$fh = new \app\admin\model\DownloadHistory;
        	$fh->save($data);
	//	echo $resend;
		//dd($resend);	
		return ajaxReturn($rData,1);
	   } else {
		
		return ajaxReturn($res['error'][0]['message']);
	   }
	   
	} else {
 	    return ajaxReturn('UPK file and SN file can not match.');
	}
   }
   public function downloadxml() {
	$id = input('get.id');
	$resend = $this->http_get('https://59.188.29.77:8443/ZKBioLicense/downloadController.do?downloadToXML&apl=zkt&licid='.$id);
        $resend = json_decode($resend);
        $resend = xml_encode($resend);
        $http = new Http();
        $http::download_content($resend, $id.'-License.xml',180,'xml');
   }
   public function curl_post($url,$fields, $timeout=20000) {
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
   }
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
