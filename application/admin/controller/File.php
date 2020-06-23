<?php
namespace app\admin\controller;
use think\Controller;
use think\Config;
use app\admin\model\File as File_m;
use think\Db;
class File extends Base
{
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new File_m;
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
        $cateData = Db::name('category')->where(['state'=>['eq',1],'type'=>['eq','file']])->field('id,name')->select();     
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->setPageBtn(lang('show_title_file'), lang('show_title_lst_file'));
        $this->assign('cateData',$cateData);
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
                    write_log(lang('add_success_file'));             
                    return ajaxReturn(lang('action_success'), url('lst'));
                } else {
                    write_log(lang('add_error_file'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_file'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $cateData = Db::name('category')->where(['state'=>['eq',1],'type'=>['eq','file']])->field('id,name')->select();
            $this->assign('cateData',$cateData);
            $this->setPageBtn(lang('show_title_file'), lang('show_title_add_file'));
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
                write_log(lang('edit_success_file'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url('lst')."?page=".input('page'));
            } else {
                write_log(lang('edit_error_file'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $cateData = Db::name('category')->where(['state'=>['eq',1],'type'=>['eq','file']])->field('id,name')->select();
                $this->assign('cateData',$cateData);
                $this->setPageBtn(lang('show_title_file'), lang('show_title_edit_file'));
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
                        db('download_history')->where([ 'file_id' => ['in', $id_arr] ])->delete();                   
                        write_log(lang('del_success_file'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    }else{
                        write_log(lang('del_error_file'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_file'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }	
	}
}