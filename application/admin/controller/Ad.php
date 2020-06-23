<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Ad as ad_m;
use think\Db;
class Ad extends Base
{
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new ad_m;
	}
    /**
     * [index 首页广告]
     */
    public function index()
    {
        $where = [];
        $type = 'index';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.display_position')) {
            $where['display_position'] = ['eq',trim(input('get.display_position'))];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                      //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [index 新闻广告]
     */
    public function news()
    {
        $where = [];
        $type = 'news';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.display_position')) {
            $where['display_position'] = ['eq',trim(input('get.display_position'))];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                      //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [index 招聘广告]
     */
    public function recruit()
    {
        $where = [];
        $type = 'recruit';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.display_position')) {
            $where['display_position'] = ['eq',trim(input('get.display_position'))];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                       //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [service 服务支持广告]
     */
    public function service()
    {
        $where = [];
        $type = 'service';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.display_position')) {
            $where['display_position'] = ['eq',trim(input('get.display_position'))];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                      //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [aboutus 关于我们广告]
     */
    public function aboutus()
    {
        $where = [];
        $type = 'aboutus';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('position')) {
            if (trim(input('position')) == 'banner' ) {
                $where['display_position'] = ['like',"%banner%"];
            } else {
                $search = trim(input('position'));
                switch ($search) {
                    case '荣誉资质-图片':
                        $where['display_position'] = [['eq','荣誉资质-图片'],['eq','荣誉资质-文字'],'or'];
                        break;
                    case '质量管理-认证证书':
                        $where['display_position'] = ['in',['质量管理-视频','质量管理-证书标志','质量管理-认证证书','质量管理-中-图文']];
                        break;
                    default:
                        $where['display_position'] = ['eq',trim(input('position'))];
                        break;
                }
                
            }
        } else {
            $where['display_position'] = [['not like','%banner%'],['not in',['质量管理-认证证书','质量管理-证书标志','质量管理-视频','质量管理-中-图文','荣誉资质-图片','荣誉资质-文字','关于我们-跳转图标','关于我们-跳转图标','关于我们-合作客户']],'and'];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                      //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [partner 合作伙伴广告]
     */
    public function partner()
    {
        $where = [];
        $type = 'partner';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.display_position')) {
            $where['display_position'] = ['eq',trim(input('get.display_position'))];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                      //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [Contact us 联系我们]
     */
    public function contactus()
    {
        $where = [];
        $type = 'contactus';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.display_position')) {
            $where['display_position'] = ['eq',trim(input('get.display_position'))];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                       //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [product 产品广告]
     */
    public function product()
    {
        $where = [];
        $type = 'product';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.display_position')) {
            $where['display_position'] = ['eq',trim(input('get.display_position'))];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                       //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [solution 解决方案广告]
     */
    public function solution()
    {
        $where = [];
        $type = 'solution';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.display_position')) {
            $where['display_position'] = ['eq',trim(input('get.display_position'))];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                       //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [online_store 如何购买广告]
     */
    public function online_store()
    {
        $where = [];
        $type = 'online_store';                                                                                    
        if (input('get.search')) {
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.display_position')) {
            $where['display_position'] = ['eq',trim(input('get.display_position'))];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                       //默认顺序
        }
        $where['type'] = $type;
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_lst_ad'));
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('lst');
    }
    /**
     * [add_index 增加首页广告]
     */
    public function add_index()
    {
        $type = 'index';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad'));             
                    return ajaxReturn(lang('action_success'), url($type));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        }else{
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [add_recruit 增加招聘广告]
     */
    public function add_recruit()
    {
        $type = 'recruit';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad'));             
                    return ajaxReturn(lang('action_success'), url($type));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        }else{
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [add_news 增加新闻广告]
     */
    public function add_news()
    {
        $type = 'news';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad'));             
                    return ajaxReturn(lang('action_success'), url($type));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        }else{
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [add_service 增加服务支持广告]
     */
    public function add_service()
    {
        $type = 'service';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad'));             
                    return ajaxReturn(lang('action_success'), url($type));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        }else{
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [add_aboutus 增加关于我们广告]
     */
    public function add_aboutus()
    {
        $type = 'aboutus';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad')); 
                    $position = !empty(input('position'))?input('position'):'';      
                    return ajaxReturn(lang('action_success'), url($type,['position'=>$position]));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        }else{
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [add_partner 增加合作伙伴广告]
     */
    public function add_partner()
    {
        $type = 'partner';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad'));             
                    return ajaxReturn(lang('action_success'), url($type));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [add_contactus 增加联系我们广告]
     */
    public function add_contactus()
    {
        $type = 'contactus';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad'));             
                    return ajaxReturn(lang('action_success'), url($type));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [add_product 增加产品广告]
     */
    public function add_product()
    {
        $type = 'product';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad'));             
                    return ajaxReturn(lang('action_success'), url($type));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [add_solution 增加解决方案广告]
     */
    public function add_solution()
    {
        $type = 'solution';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad'));             
                    return ajaxReturn(lang('action_success'), url($type));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [add_online_store 增加如何购买广告]
     */
    public function add_online_store()
    {
        $type = 'online_store';
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_ad'));             
                    return ajaxReturn(lang('action_success'), url($type));
                } else {
                    write_log(lang('add_error_ad'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_ad'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_add_ad'));
            $ad = config('ad_'.$type.'_list');
            $this->assign('ad',$ad);
            $this->assign('type',$type); 
            return $this->fetch('add');
        }
    }
    /**
     * [edit_index 修改首页广告]
     */
    public function edit_index($id)
    {
        $type = 'index';
        if (request()->isPost()){
            $data = input('post.');
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            }else{
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result){
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url($type)."?page=".input('page'));
            }else{
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            if ($id > 0){
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    /**
     * [edit_service 修改服务支持广告]
     */
    public function edit_service($id)
    {
        $type = 'service';
        if (request()->isPost()){
            $data = input('post.');
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            }else{
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result){
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url($type)."?page=".input('page'));
            }else{
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            if ($id > 0){
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    /**
     * [edit_news 修改新闻广告]
     */
    public function edit_news($id)
    {
        $type = 'news';
        if (request()->isPost()){
            $data = input('post.');
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            }else{
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result){
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url($type)."?page=".input('page'));
            }else{
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            if ($id > 0){
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    /**
     * [edit_recruit 修改招聘广告]
     */
    public function edit_recruit($id)
    {
        $type = 'recruit';
        if (request()->isPost()) {
            $data = input('post.');
            if (count($data) == 2) {
                foreach ($data as $k =>$v) {
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            } else {
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result) {
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url($type)."?page=".input('page'));
            } else {
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    /**
     * [edit_aboutus 修改联系我们广告]
     */
    public function edit_aboutus($id)
    {
        $type = 'aboutus';
        if (request()->isPost()) {
            $data = input('post.');
            if (count($data) == 2) {
                foreach ($data as $k =>$v) {
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            } else {
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result) {
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                $position = !empty(input('position'))?input('position'):'';
                return ajaxReturn(lang('action_success'), url($type,['position'=>$position])."?page=".input('page'));
            } else {
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    /**
     * [edit_partner 修改合作伙伴广告]
     */
    public function edit_partner($id)
    {
        $type = 'partner';
        if (request()->isPost()) {
            $data = input('post.');
            if (count($data) == 2) {
                foreach ($data as $k =>$v) {
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            } else {
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result) {
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url($type)."?page=".input('page'));
            } else {
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    /**
     * [edit_contactus 修改联系我们广告]
     */
    public function edit_contactus($id)
    {
        $type = 'contactus';
        if (request()->isPost()) {
            $data = input('post.');
            if (count($data) == 2) {
                foreach ($data as $k =>$v) {
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            } else {
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result) {
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url($type)."?page=".input('page'));
            } else {
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    public function edit_product($id)
    {
        $type = 'product';
        if (request()->isPost()) {
            $data = input('post.');
            if (count($data) == 2) {
                foreach ($data as $k =>$v) {
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            } else {
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result) {
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url($type)."?page=".input('page'));
            } else {
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    public function edit_solution($id)
    {
        $type = 'solution';
        if (request()->isPost()) {
            $data = input('post.');
            if (count($data) == 2) {
                foreach ($data as $k =>$v) {
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            } else {
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result) {
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url($type)."?page=".input('page'));
            } else {
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    public function edit_online_store($id)
    {
        $type = 'online_store';
        if (request()->isPost()) {
            $data = input('post.');
            if (count($data) == 2) {
                foreach ($data as $k =>$v) {
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            } else {
                $data['type'] = $type;
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result) {
                write_log(lang('edit_success_ad'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url($type)."?page=".input('page'));
            } else {
                write_log(lang('edit_error_ad'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $ad = config('ad_'.$type.'_list');
                $this->setPageBtn(lang('show_title_ad_'.$type), lang('show_title_edit_ad'));               
                $this->assign('ad',$ad);                 
                $this->assign('data', $data);
                return $this->fetch('edit');
            }
        }
    }
    /**
     * [delete_index 删除首页广告]
     */
    public function delete_index()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('index'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }
        
    }
    /**
     * [delete_recruit 删除招聘广告]
     */
    public function delete_recruit()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('recruit'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }
        
    }
    /**
     * [delete_service 删除服务支持广告]
     */
    public function delete_service()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('service'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }
        
    }
    /**
     * [delete_news 删除新闻广告]
     */
    public function delete_news()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('news'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }
        
    }
    /**
     * [delete_aboutus 删除关于我们广告]
     */
    public function delete_aboutus()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('aboutus'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }
        
    }
    /**
     * [delete_contactus 删除联系我们广告]
     */
    public function delete_contactus()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('contactus'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }        
    }
    /**
     * [delete_partner 删除合作伙伴广告]
     */
    public function delete_partner()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('partner'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }       
    }
    /**
     * [delete_product 删除产品广告]
     */
    public function delete_product()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('product'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }
        
    }
    /**
     * [delete_solution 删除解决方案广告]
     */
    public function delete_solution()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('solution'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }
        
    }
    /**
     * [delete_online_store 删除如何购买广告]
     */
    public function delete_online_store()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                @unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_ad'));
                        return ajaxReturn(lang('action_success'), url('online_store'));
                    }else{
                        write_log(lang('del_error_ad'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_ad'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }
        
    }
  
}
