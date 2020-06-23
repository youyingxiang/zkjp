<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\News as news_m;
use think\Db;
class News extends Base
{
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new news_m;
	}

	public function lst()
    {
    	$where = [];
    	if (input('get.search')) {
            $where['title|content'] = ['like', '%'.trim(input('get.search')).'%'];                   //搜索条件
        }
        if (input('get.typeid')) {
            $where['typeid'] = ['eq',trim(input('get.typeid'))];                   //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        } else {
            $order = 'update_time desc';
        }
        $data = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $typeData = Db::name('category')->where(['state'=>1,'type'=>'news'])->order('order_key  asc')->select();
        $flagData = config('flag_news');
        $this->assign('flagData',$flagData);
        $this->assign('typeData',$typeData);
        $this->assign('data', $data);
        $this->setPageBtn(lang('show_title_news'), lang('show_title_lst_news'));
        return $this->fetch();
    }

    public function add()
    {
    	if (request()->isPost()) {
            try{
                $data = input('post.');
                if(!isset($data['flag']))$data['flag'] = '';
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);             
                if ($result) {
                    write_log(lang('add_success_news'));             
                    return ajaxReturn(lang('action_success'), url('lst'));
                } else {
                    write_log(lang('add_error_news'));
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) { 
                write_log(lang('add_error_news'));               
                return ajaxReturn($e->getMessage());   
            }
        } else {
            $typeData = Db::name('category')->where(['state'=>1,'type'=>'news'])->order('order_key asc')->select();
            $flagData = config('flag_news');
            $this->setPageBtn(lang('show_title_news'), lang('show_title_add_news'));
            $this->assign('flagData',$flagData);
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
                if(!isset($data['flag']))$data['flag'] = '';
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if (false !== $result) {
                write_log(lang('edit_success_news'));
                $page = !empty(input('page'))?input('page'):'';
                return ajaxReturn(lang('action_success'), url('lst')."?page=".input('page'));
            } else {
                write_log(lang('edit_error_news'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            if ($id > 0) {
                $data = $this->cModel->get($id);
                $typeData = Db::name('category')->where(['state'=>1,'type'=>'news'])->order('order_key asc')->select();
                $flagData = config('flag_news');
                $this->setPageBtn(lang('show_title_news'), lang('show_title_edit_news'));
                $this->assign('flagData',$flagData);
                $this->assign('typeData',$typeData);
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
                        write_log(lang('del_success_news'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    } else {
                        write_log(lang('del_error_news'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_news'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }	
	}
    /**
     * [push 邮箱推送内容]
     * @return [type] [description]
     */
    public function push()
    {
        $id = intval(trim(input('post.id')));
        $data = db('news')->field('typeid,title,content')->find($id);
        if (!empty($data)) {
            $persons = db('subscribe')->where(['newscat_id'=>['eq',$data['typeid']]])->column('user_email');
            if (count($persons) > 0) {
                $res = sendMail($persons,$data['title'],$data['content']);
                if (!empty($res) && $res == 1) {
                    write_log(lang('email_push_s'));
                    return ajaxReturn(lang('action_success'),1);    //推送成功
                } else {
                    write_log(lang('email_push_e'));
                    return ajaxReturn(lang('action_fail'));    //推送失败
                }
	    } else {
	        return ajaxReturn('没人订阅发送什么？');
	    }
        }
        return ajaxReturn(lang('action_fail'));    //推送失败
    }
}
