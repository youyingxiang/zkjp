<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Category as cat;
use think\Db;
class Category extends Base
{
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new cat;
	}
    public function news()
    {
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'order_key asc';
        }
        $data = $this->cModel->getTree($order,$where = ['type'=>'news']);
        $this->assign('data', $data);
        $this->assign('type','news');
        $this->setPageBtn(lang('show_title_news'), lang('show_title_lst_cat'));
        return $this->fetch('lst');
    }
    public function product()
    {
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'order_key asc';
        }
        $data = $this->cModel->getTree($order,$where = ['type'=>'product']);
        $this->assign('data', $data);
        $this->assign('type','product');
        $this->setPageBtn(lang('show_title_product'), lang('show_title_lst_cat'));
        return $this->fetch('lst');
    }
    public function file()
    {
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'order_key asc';
        }
        $data = $this->cModel->getTree($order,$where = ['type'=>'file']);
        $this->assign('data', $data);
        $this->assign('type','file');
        $this->setPageBtn(lang('show_title_file'), lang('show_title_lst_cat'));
        return $this->fetch('lst');
    }
    public function add_news()
    {
        $type = 'news';
        if (request()->isPost()) {          
            $data = input('post.');
            $data['type'] = $type;
            $result = $this->cModel->allowField(true)->validate(CONTROLLER_NAME.'.add')->save($data);
            if ($result) {
                write_log(lang('add_success_cat'));
                return ajaxReturn(lang('add_success_cat'), url($type));
            } else { 
                write_log(lang('add_error_cat'));
                return ajaxReturn($this->cModel->getError());
            }
        }
        $order = 'order_key asc';
        $parentData = $this->cModel->getTree($order,$where = ['state'=>1,'type'=>$type]);
        $this->assign('parentData', $parentData);
        $this->assign('type',$type);
        $this->setPageBtn(lang('show_title_'.$type), lang('show_title_add_cat'));
        return $this->fetch('add');
    }
    public function add_product()
    {
        $type = 'product';
        if (request()->isPost()) {          
            $data = input('post.');
            $data['type'] = $type;
            $result = $this->cModel->allowField(true)->validate(CONTROLLER_NAME.'.add')->save($data);
            if (!empty($data['banner'])) {
                db('category_banner')->insert(['img'=>$data['banner'],'cat_id'=>$this->cModel->id,'type'=>'product']);
            }
            if ($result) {
                write_log(lang('add_success_cat'));
                return ajaxReturn(lang('add_success_cat'), url($type));
            } else { 
                write_log(lang('add_error_cat'));
                return ajaxReturn($this->cModel->getError());
            }
        }
        $order = 'order_key asc';
        $parentData = $this->cModel->getTree($order,$where = ['state'=>1,'type'=>$type]);
        $this->assign('type',$type);
        $this->assign('parentData', $parentData);
        $this->setPageBtn(lang('show_title_'.$type), lang('show_title_add_cat'));
        return $this->fetch('add');
    }
    public function add_file()
    {

        $type = 'file';
        if (request()->isPost()) {   

            $data = input('post.');
           
            $data['type'] = $type;
            $result = $this->cModel->allowField(true)->validate(CONTROLLER_NAME.'.add')->save($data);
            
            if (!empty($data['down_auth']) && is_array($data['down_auth'])) {
                foreach ($data['down_auth'] as $k => $v) {
                    db('category_download')->insert(['category_id'=>$this->cModel->id,'user_type'=>$v]);
                }
            }
            if ($result) {
                write_log(lang('add_success_cat'));
                return ajaxReturn(lang('add_success_cat'), url($type));
            } else { 
                write_log(lang('add_error_cat'));
                return ajaxReturn($this->cModel->getError());
            }
        }
        $order = 'order_key asc';
        $parentData = $this->cModel->getTree($order,$where = ['state'=>1,'type'=>$type]);
        $this->assign('type',$type);
        $this->assign('parentData', $parentData);
        $this->setPageBtn(lang('show_title_'.$type), lang('show_title_add_cat'));
        return $this->fetch('add');
    }


    public function edit_news($id)
    {
        $type = 'news';
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
            if (FALSE !== $result) {
                write_log(lang('edit_success_cat'));
                return ajaxReturn(lang('action_success'), url($type)); 
            } else {
                write_log(lang('edit_error_cat'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            $order = 'order_key asc';
            $data = $this->cModel->get($id)->getData();
            $parentData = $this->cModel->getTree($order,$where = ['state'=>1,'type'=>$type]);
            $this->setPageBtn(lang('show_title_'.$type), lang('show_title_edit_cat'));
            $this->assign('data', $data);
            $this->assign('type',$type);                                        
            $this->assign('parentData', $parentData);
            return $this->fetch('edit');
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
                db('category_banner')->where(['cat_id'=>['eq',$id],'type'=>['eq','product']])->delete();
                if (!empty($data['banner'])) {
                    db('category_banner')->insert(['img'=>$data['banner'],'cat_id'=>$id,'type'=>'product']);
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (FALSE !== $result) {
                write_log(lang('edit_success_cat'));
                return ajaxReturn(lang('action_success'), url($type)); 
            } else {
                write_log(lang('edit_error_cat'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            $order = 'order_key asc';
            $data = $this->cModel->get($id)->getData();
            $parentData = $this->cModel->getTree($order,$where = ['state'=>1,'type'=>$type]);
            $data['banner'] = db('category_banner')->where(['type'=>['eq','product'],'cat_id'=>['eq',$id]])->value('img');
            $this->setPageBtn(lang('show_title_'.$type), lang('show_title_edit_cat'));
            $this->assign('type',$type);
            $this->assign('data', $data);                                        
            $this->assign('parentData', $parentData);
            return $this->fetch('edit');
        }
    }
    public function edit_file($id)
    {
        $type = 'file';
        if (request()->isPost()) {
            $data = input('post.');         
            if (count($data) == 2) {
                foreach ($data as $k =>$v) {
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            } else {
                $data['type'] = $type;
                db('category_download')->where(['category_id'=>['eq',$id]])->delete();
                if (!empty($data['down_auth']) && is_array($data['down_auth'])) {           
                    foreach ($data['down_auth'] as $k => $v) {
                        db('category_download')->insert(['category_id'=>$id,'user_type'=>$v]);
                    }
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (FALSE !== $result) {
                write_log(lang('edit_success_cat'));
                return ajaxReturn(lang('action_success'), url($type)); 
            } else {
                write_log(lang('edit_error_cat'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            $order = 'order_key asc';
            $data = $this->cModel->get($id)->getData();
            $auth_cate_down = db('category_download')->where(['category_id'=>['eq',$id]])->column('user_type'); //下载权限
            $parentData = $this->cModel->getTree($order,$where = ['state'=>1,'type'=>$type]);
            $this->setPageBtn(lang('show_title_'.$type), lang('show_title_edit_cat'));
            $this->assign('auth_cate_down',$auth_cate_down);
            $this->assign('type',$type);
            $this->assign('data', $data);                                        
            $this->assign('parentData', $parentData);
            return $this->fetch('edit');
        }
    }

    public function delete_news()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id) ) {
                $id_arr = $this->cModel->getChildren($id);
                $id_arr[] = intval($id);
                $result = $this->cModel->destroy($id_arr);
                if (FALSE !== $result) {
                    write_log(lang('del_success_cat'));
                    return ajaxReturn(lang('action_success'), url('news'));
                } else {
                    write_log(lang('del_error_cat'));
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }   
    }
    public function delete_product()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id) ) {
                $id_arr = $this->cModel->getChildren($id);
                $id_arr[] = intval($id);
                $result = $this->cModel->destroy($id_arr);
                if (FALSE !== $result) {
                    db('category_banner')->where(['cat_id'=>['eq',$id],'type'=>['eq','product']])->delete();
                    write_log(lang('del_success_cat'));
                    return ajaxReturn(lang('action_success'), url('product'));
                } else {
                    write_log(lang('del_error_cat'));
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }   
    } 
    public function delete_file()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id) ) {
                $id_arr = $this->cModel->getChildren($id);
                $id_arr[] = intval($id);
                $result = $this->cModel->destroy($id_arr);
                if (FALSE !== $result) {
                    db('category_download')->where(['category_id'=>['eq',$id]])->delete();
                    write_log(lang('del_success_cat'));
                    return ajaxReturn(lang('action_success'), url('file'));
                } else {
                    write_log(lang('del_error_cat'));
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }   
    }  
}