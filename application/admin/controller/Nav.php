<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Nav as nav_m;
use think\Db;
class Nav extends Base
{
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new nav_m;
	}
    public function lst()
    {
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'order_key asc';
        }
        $data = $this->cModel->getTree($order);
        $this->assign('data', $data);
        $this->setPageBtn(lang('show_title_config'), lang('show_title_lst_nav'));
        return $this->fetch();
    }
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $result = $this->cModel->allowField(true)->validate(CONTROLLER_NAME.'.add')->save($data);
            if ($result) {
                write_log(lang('add_success_nav'));
                return ajaxReturn(lang('add_success_nav'), url('lst'));
            } else { 
                write_log(lang('add_error_nav'));
                return ajaxReturn($this->cModel->getError());
            }
        }
        $order = 'order_key asc';
        $parentData = $this->cModel->getTree($order );
        $this->assign('parentData', $parentData);
        $this->setPageBtn(lang('show_title_config'), lang('show_title_add_nav'));
        return $this->fetch();
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
            if (FALSE !== $result) {
                write_log(lang('edit_success_nav'));
                return ajaxReturn(lang('action_success'), url('lst')); 
            } else {
                write_log(lang('edit_error_nav'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            $data = $this->cModel->get($id)->getData();
            $order = 'order_key asc';
            $parentData = $this->cModel->getTree($order );
            $this->setPageBtn(lang('show_title_config'), lang('show_title_edit_nav'));
            $this->assign('data', $data);                                        
            $this->assign('parentData', $parentData);
            return $this->fetch();
        }
    }

    public function delete()
    {
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id) ) {
                $id_arr = $this->cModel->getChildren($id);
                $id_arr[] = intval($id);
                $result = $this->cModel->destroy($id_arr);
                if (FALSE !== $result) {
                    write_log(lang('del_success_nav'));
                    return ajaxReturn(lang('action_success'), url('lst'));
                } else {
                    write_log(lang('del_error_nav'));
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }   
    } 
}