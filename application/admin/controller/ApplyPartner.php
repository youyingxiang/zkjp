<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\ApplyPartner as ap_m;

class ApplyPartner extends Base
{	
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new ap_m;
	}

	public function lst()
    {
        $where = [];																					
        if (input('get.search')) {
            $where['name|job|company_name|phone|email|city|address'] = ['like', '%'.trim(input('get.search')).'%'];			        //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));												//排序条件
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'id desc';																		//默认顺序
        }
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_applypartner'), lang('show_title_lst_applypartner'));
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
                    write_log(lang('add_success_applypartner'));             
                    return ajaxReturn(lang('action_success'), url('lst'));
                } else {
                    write_log(lang('add_error_applypartner'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_applypartner'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $this->setPageBtn(lang('show_title_applypartner'), lang('show_title_add_applypartner'));
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
            if (false !== $result) {
                write_log(lang('edit_success_applypartner'));
                return ajaxReturn(lang('action_success'), url('lst'));
            } else {
                write_log(lang('edit_error_applypartner'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $this->setPageBtn(lang('show_title_applypartner'), lang('show_title_edit_applypartner'));                
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
                    $result = $this->cModel->where($where)->delete();   //删除主表数据                   
                    if ($result !== false ) {                        
                        write_log(lang('del_success_applypartner'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    } else {
                       write_log(lang('del_error_applypartner')); 
                       return ajaxReturn($this->cModel->getError());             
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_applypartner'));             
                    return ajaxReturn($e->getMessage());
                }
            }
        }		
	}
}