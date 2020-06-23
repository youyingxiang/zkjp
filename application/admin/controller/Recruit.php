<?php
namespace app\admin\controller;
use think\Controller;
use think\Config;
use app\admin\model\Recruit as recruit_m;
use app\admin\controller\Alone;
use think\Db;
class Recruit extends Base
{
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new recruit_m;
	}
	public function lst()
	{
		$where = [];																					
        if (input('get.search')) {
            $where['name|num'] = ['like', '%'.trim(input('get.search')).'%'];			        //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));												//排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';																		//默认顺序
        }
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_recruit'), lang('show_title_lst_recruit'));
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
                    write_log(lang('add_success_recruit'));             
                    return ajaxReturn(lang('action_success'), url('lst'));
                } else {
                    write_log(lang('add_error_recruit'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_recruit'));               
                return ajaxReturn($e->getMessage());   
            }
        }else{
            $alone = new Alone;
            $province = config('continent');
            $postData = Db::name('post')->where('state',1)->field('id,job_name')->order('order_key asc')->select();
            $nature = config('work_nature');          
            $this->setPageBtn(lang('show_title_recruit'), lang('show_title_add_recruit'));
            $this->assign('province',$province);
            $this->assign('postData',$postData);
            $this->assign('nature',$nature);
            return $this->fetch();
        }
    }
	public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            }else{
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result){
                write_log(lang('edit_success_recruit'));
                return ajaxReturn(lang('action_success'), url('lst'));
            }else{
                write_log(lang('edit_error_recruit'));
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            if ($id > 0){
                $alone = new Alone;
                $province = config('continent');
                $data = $this->cModel->get($id)->getData();
                $nature = config('work_nature');
                $postData = Db::name('post')->where('state',1)->field('id,job_name')->order('order_key asc')->select();
                $this->setPageBtn(lang('show_title_recruit'), lang('show_title_edit_recruit'));
                $this->assign('province',$province);
                $this->assign('postData',$postData);
                $this->assign('nature',$nature);
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
                        write_log(lang('del_success_recruit'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    }else{
                        write_log(lang('del_error_recruit'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_recruit'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }
		
	}
}