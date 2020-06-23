<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Develop as Develop_m;

class Develop extends Base
{	
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new Develop_m;
	}

	public function lst()
    {
        $where = [];																					
        if (input('get.search')) {
            $where['title|abstract'] = ['like', '%'.trim(input('get.search')).'%'];			        //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));												//排序条件
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'id asc';																		//默认顺序
        }
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_develop'), lang('show_title_lst_develop'));
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
                    write_log(lang('add_success_develop'));             
                    return ajaxReturn(lang('action_success'), url('lst'));
                } else {
                    write_log(lang('add_error_develop'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_develop'));               
                return ajaxReturn($e->getMessage());   
            }
        }else{
            $this->setPageBtn(lang('show_title_develop'), lang('show_title_add_develop'));
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
                write_log(lang('edit_success_develop'));
                return ajaxReturn(lang('action_success'), url('lst'));
            } else {
                write_log(lang('edit_error_develop'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $this->setPageBtn(lang('show_title_develop'), lang('show_title_edit_develop'));                
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
                    if ($result !== false ) {                        
                        foreach ($data as $k => $v){
                            if ($v['img'] != '/cn/static/global/face/default.png'){
                                unlink(WEB_PATH.$v['img']);          //删除头像文件
                            }
                        }
                        write_log(lang('del_success_develop'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    } else {
                        write_log(lang('del_error_develop'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_develop'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }		
	}
}