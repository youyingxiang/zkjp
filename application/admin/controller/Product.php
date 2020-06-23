<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Product as Product_m;
use app\admin\model\Category as cat;
use app\admin\model\File as File_m;
use app\admin\model\Cases as Cases_m;
use app\admin\model\Scheme as Scheme_m;
use think\Db;

class Product extends Base
{   
    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Product_m;
    }

    public function lst()
    {
        $where = [];
        $cat = new cat;                                                                                 
        if (input('get.search')) {
            $where['name'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.typeid')) {
            $ids = $cat->getChildren(trim(input('get.typeid')));
            $ids [] = trim(input('get.typeid'));
            $pid = db('product_category')->where(['category_id'=>['in',$ids]])->column('product_id');       //取出在这些分类下的商品        
            $where['id'] = ['in',$pid];
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'order_key asc';                                                                       //默认顺序
        }
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_product'), lang('show_title_lst_product'));
        $this->assign('data',$data);
        $typeData = $cat->getTree($order,$where = ['type'=>'product']);
        $this->assign('typeData',$typeData);
        return $this->fetch();
    }
    public function add()
    {
        if (request()->isPost()) {
            try{
                $data = input('post.');
                $data['flag'] = empty($data['flag'])?[]:$data['flag'];
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);                                    
                if ($result) {
                    $m = $this->cModel->get($this->cModel->id);
                    if(!empty($data['product_img']))$m->productImg()->saveAllByKey($data['product_img'],'img');             //增加图片
                    if(!empty($data['product_cat']))$m->productCategory()->saveAll(array_unique($data['product_cat']));     //增加分类
                    write_log(lang('add_success_product'));             
                    return ajaxReturn(lang('action_success'), url('lst'));
                } else {
                    write_log(lang('add_error_product'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_product'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $cat = new cat;
            $order = 'order_key asc';
            $typeData = $cat->getTree($order,$where = ['type'=>'product']);
            $this->setPageBtn(lang('show_title_product'), lang('show_title_add_product'));
            $this->assign('typeData',$typeData);
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
                $data['flag'] = empty($data['flag'])?[]:$data['flag'];
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
                if (false !== $result) {
                    $m = $this->cModel->get($this->cModel->id);
                    db('product_category')->where(['product_id' => $this->cModel->id])->delete();
                    db('product_img')->where(['product_id' => $this->cModel->id])->delete();
                    if(!empty($data['product_img']))$m->productImg()->saveAllByKey($data['product_img'],'img');             //增加图片
                    if(!empty($data['product_cat']))$m->productCategory()->saveAll(array_unique($data['product_cat']));       //增加分类
                }
            }
            if (false !== $result) {
                write_log(lang('edit_success_product'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url('lst')."?page=".input('page'));
            } else {
                write_log(lang('edit_error_product'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $cat = new cat;
                $order = 'order_key asc';
                $typeData = $cat->getTree($order,$where = ['type'=>'product']);
                
                // $info = $this->cModel->all();
                // foreach ($info as $key => $value) {
                //     $data = [];
                //     foreach ($value['product_material']['img'] as $k => $v) {
                //         if (!empty($v) && getSubstr($v,0,3) !='/en') {
                //             $data['product_material']['img'][] = '/en'.$v;
                //         } else {
                //             if (!empty($v)) {
                //                 $data['product_material']['img'][] = $v;
                //             }                    
                //         }
                //         if (!empty($value['product_material']['title'][$k])) {
                //             $data['product_material']['title'][] = $value['product_material']['title'][$k];
                //         }
                        
                //     }

                //     foreach ($value['product_trait']['img'] as $k => $v) {
                //         if (!empty($v) && getSubstr($v,0,3) !='/en') {
                //             $data['product_trait']['img'][] = '/en'.$v;
                //         } else {
                //             if (!empty($v)) {
                //                 $data['product_trait']['img'][] = $v;
                //             }
                //         }
                //         if (!empty($value['product_trait']['content'][$k])) {
                //             $data['product_trait']['content'][] = $value['product_trait']['content'][$k];
                //         }
                //     }
                //     if (count($data)>0) {
                //         dump($data);
                //         dump($value->getdata()['id']);
                //         $data['id'] = $value->getdata()['id'];

                //         $this->cModel->allowField(true)->save($data, $value->getdata()['id']);
                //     }

                // }
                // dd($data);
                // exit;
                $data = $this->cModel->get($id);
                $this->setPageBtn(lang('show_title_product'), lang('show_title_edit_product'));                
                $this->assign('data', $data);
                $this->assign('typeData',$typeData);
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
                    db('product_category')->where(['product_id' => ['in', $id_arr]])->delete();
                    db('product_img')->where(['product_id' => ['in', $id_arr]])->delete();
                    db('product_file')->where(['product_id' => ['in', $id_arr]])->delete();
                    db('product_cases')->where(['product_id' => ['in', $id_arr]])->delete();
                    db('product_scheme')->where(['product_id' => ['in', $id_arr]])->delete();
                    db('product_self')->where(['product_id_master' => ['in', $id_arr]])->delete();               
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_product'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    } else {
                        write_log(lang('del_error_product'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_product'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }       
    }
    /**
     * [relation_f 关联文件]
     * @return [type] [description]
     */
    public function relation_f()
    {
        $file = new File_m;
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
            $product_id = intval(trim(input('id')));
            $data_f = db('product_file')->where(['product_id'=>$product_id])->column('file_id'); 
            $this->assign('data_f', $data_f);
        }    
        $this->assign('data',$data);
        $this->setPageBtn(lang('show_title_product'), lang('show_title_lst_file'));
        return $this->fetch();
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
            $product_id = intval(trim(input('id')));
            $where['id'] = ['neq',$product_id]; 
            $data_f = db('product_self')->where(['product_id_master'=>$product_id])->column('product_id'); 
            $this->assign('data_f',$data_f);
        }
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_product'), lang('show_title_lst_product'));
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
            $product_id = intval(trim(input('id')));
            $data_f = db('product_cases')->where(['product_id'=>$product_id])->column('cases_id'); 
            $this->assign('data_f',$data_f);
        }
        $c = new Cases_m;
        $data = $c->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_product'), lang('show_title_lst_cases'));
        $this->assign('data',$data);     
        return $this->fetch();
    }
     /**
     * [relation_s 关联方案]
     * @return [type] [description]
     */
    public function relation_s()
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
            $product_id = intval(trim(input('id')));
            $data_f = db('product_scheme')->where(['product_id'=>$product_id])->column('scheme_id'); 
            $this->assign('data_f',$data_f);
        }
        $s = new Scheme_m;
        $data = $s->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_product'), lang('show_title_lst_scheme'));
        $this->assign('data',$data);     
        return $this->fetch();
    }
    public function test()
    {
        // $img = ['/uploads/image/20171020/36dc4d0bc547a24cff088407c9b2f249.jpg',
        // '/uploads/image/20171020/36dc4d0bc547a24cff088407c9b2f249.jpg',
        // '/uploads/image/20171020/36dc4d0bc547a24cff088407c9b2f249.jpg'];
        // $res = $this->cModel->find(1);
        // dd($res);
        // dd($res->productImg()->saveAllByKey($img,'img'));
    }
}