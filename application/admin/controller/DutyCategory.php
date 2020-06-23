<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\DutyCategory as dcat;
use think\Db;
class DutyCategory extends Base
{
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new dcat;
	}
    public function lst()
    {
        $where= [];
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'order_key asc';
        }
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->assign('data', $data);
        $this->setPageBtn(lang('show_title_dutyc'), lang('show_title_lst_dutyc'));
        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {          
            $data = input('post.');
            $result = $this->cModel->allowField(true)->validate(CONTROLLER_NAME.'.add')->save($data);
            if ($result) {
                write_log(lang('add_success_dutyc'));
                return ajaxReturn(lang('add_success_dutyc'), url('lst'));
            } else { 
                write_log(lang('add_error_dutyc'));
                return ajaxReturn($this->cModel->getError());
            }
        }
        $this->setPageBtn(lang('show_title_dutyc'), lang('show_title_add_dutyc'));
        return $this->fetch('add');
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
                write_log(lang('edit_success_dutyc'));
                return ajaxReturn(lang('action_success'), url('lst')); 
            } else {
                write_log(lang('edit_error_dutyc'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            $this->setPageBtn(lang('show_title_dutyc'), lang('show_title_edit_dutyc'));
            $data = $this->cModel->get($id)->getData();
            $this->assign('data', $data);
            return $this->fetch();
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
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v) {
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_dutyc'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    } else {
                        write_log(lang('del_error_dutyc'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_dutyc'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }     
    }
}