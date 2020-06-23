<?php
namespace app\admin\controller;
use think\Controller;
use think\Config;
use app\admin\model\Cases as Cases_m;
use app\admin\model\Product as product_m;
use app\admin\model\File as file_m;
use think\Db;
class Cases extends Base
{
    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Cases_m;
    }
    public function lst()
    {
        $where = [];                                                                                    
        if (input('get.search')) {
            $where['name'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                       //默认顺序
        }
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_cases'), lang('show_title_lst_cases'));
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function add()
    {
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_cases'));             
                    return ajaxReturn(lang('action_success'), url('lst'));
                } else {
                    write_log(lang('add_error_cases'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_cases'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $scheme = db('scheme')->field('id,name')->where(['state'=>['eq',1]])->order('order_key asc')->select();
            $this->assign('scheme',$scheme);
            $this->setPageBtn(lang('show_title_cases'), lang('show_title_add_cases'));
            return $this->fetch();
        }
    }
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('post.');
            if (count($data) == 2) {
                foreach ($data as $k =>$v) {
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            } else {
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result){
                write_log(lang('edit_success_cases'));
                return ajaxReturn(lang('action_success'), url('lst'));
            }else{
                write_log(lang('edit_error_cases'));
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            if ($id > 0){
                $data = $this->cModel->get($id);
                $scheme = db('scheme')->field('id,name')->where(['state'=>['eq',1]])->order('order_key asc')->select();
                $this->assign('scheme',$scheme);
                $this->setPageBtn(lang('show_title_cases'), lang('show_title_edit_cases'));                
                $this->assign('data', $data);
                return $this->fetch();
            }
        }
    }
    public function delete()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                try{
                    $id_arr = explode(',', $id);                        //用户数据
                    $where = [ 'id' => ['in', $id_arr] ];
                    $data = $this->cModel->field('img')->where($where)->select(); 
                    $result = $this->cModel->where($where)->delete();   //删除主表数据
                    db('product_cases')->where(['cases_id' => ['in', $id_arr]])->delete();
                    db('cases_self')->where(['cases_id' => ['in', $id_arr]])->delete();              
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_cases'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    } else {
                        write_log(lang('del_error_cases'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_cases'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }       
    }
     /**
     * [relation_p 关联商品]
     * @return [type] [description]
     */
    public function relation_p()
    {
        $where = [];                                                                                     
        if (input('get.search')) {
            $where['name'] = ['like', '%'.trim(input('get.search')).'%'];                 //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'order_key asc';                                                                       //默认顺序
        }
        if (input('id')) {
            $cases_id = intval(trim(input('id')));
            $data_f = db('product_cases')->where(['cases_id'=>$cases_id])->column('product_id'); 
            $this->assign('data_f',$data_f);
        }
        $p = new product_m;
        $data = $p->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_cases'), lang('show_title_lst_product'));
        $this->assign('data',$data);     
        return $this->fetch();
    }
    /**
     * [relation_c 关联案例]
     * @return [type] [description]
     */
    public function relation_c()
    {
        $where = [];                                                                                      
        if (input('get.search')) {
            $where['name'] = ['like', '%'.trim(input('get.search')).'%'];                 //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'order_key asc';                                                                       //默认顺序
        }
        if (input('id')) {
            $cases_id = intval(trim(input('id')));
            $where['id'] = ['neq',$cases_id];          
            $data_f = db('cases_self')->where(['cases_id_master'=>$cases_id])->column('cases_id'); 
            $this->assign('data_f',$data_f);
        }
        $c = new Cases_m;
        $data = $c->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_cases'), lang('show_title_lst_cases'));
        $this->assign('data',$data);     
        return $this->fetch();
    }
    /**
     * [relation_f 关联文件]
     * @return [type] [description]
     */
    public function relation_f()
    {
        $file = new file_m;
        $where = [];                                                                                 
        if (input('get.search')) {
            $where['title|file_name'] = ['like', '%'.trim(input('get.search')).'%'];                  //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                       //默认顺序
        }
        $data = $file->where($where)->order($order)->paginate('', false, page_param());
        if (input('id')) {
            $cases_id = intval(trim(input('id')));
            $data_f = db('cases_file')->where(['cases_id'=>$cases_id])->column('file_id'); 
            $this->assign('data_f', $data_f);
        }    
        $this->assign('data',$data);
        $this->setPageBtn(lang('show_title_cases'), lang('show_title_lst_file'));
        return $this->fetch();
    }
}