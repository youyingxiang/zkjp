<?php
namespace app\admin\controller;
use think\Controller;
use think\Lang;
use app\admin\model\Log as log_ ;
class Log extends Base
{
	public function lst()
	{
        $where = [];
        if (input('get.search')) {
            $where['log_type|ip'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
		if (input('get._sort')) {
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'id desc';
        }
		$data = log_::where($where)->order($order)->paginate('',false, page_param());
		$this->setPageBtn(lang('show_title'), lang('show_title_lst_log'));
		$this->assign('data',$data);
		return $this->fetch();
	}
	public function delete()
    {
        $end_time = strtotime('-1week');
        $where['add_time'] = ['<',$end_time];
        $result = log_::where($where)->delete();   //删除主表数据 
        if ($result !== false ) {  
            return ajaxReturn(lang('action_success'), url('lst'));
        } else {
            return ajaxReturn($this->cModel->getError());
        }  
    }
}