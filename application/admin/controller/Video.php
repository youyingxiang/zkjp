<?php
namespace app\admin\controller;
use think\Controller;
use think\Config;
use app\admin\model\Video as Video_m;
use think\Db;
class Video extends Base
{
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new Video_m;
	}
	public function lst()
	{
		$where = [];																					
        if (input('get.search')) {
            $where['title|file_name'] = ['like', '%'.trim(input('get.search')).'%'];			        //搜索条件
        }
        if (input('get.cate_id')) {
            $where['cate_id'] = ['eq',trim(input('get.cate_id'))];                   //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));												//排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';																		//默认顺序
        }  
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_video'), lang('show_title_lst_video'));
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
                    write_log(lang('add_success_video'));             
                    return ajaxReturn(lang('action_success'), url('lst'));
                } else {
                    write_log(lang('add_error_video'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_video'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $this->setPageBtn(lang('show_title_video'), lang('show_title_add_video'));
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
                write_log(lang('edit_success_video'));
                return ajaxReturn(lang('action_success'), url('lst'));
            } else {
                write_log(lang('edit_error_video'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $this->setPageBtn(lang('show_title_video'), lang('show_title_edit_video'));
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
                        write_log(lang('del_success_video'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    }else{
                        write_log(lang('del_error_video'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_video'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }	
	}
}