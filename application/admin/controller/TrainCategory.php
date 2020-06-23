<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\TrainCategory as train_m;
use app\admin\model\File as File_m;
use app\admin\model\Video as Video_m;
use think\Db;
class TrainCategory extends Base
{
	private $cModel;   //当前控制器关联模型

	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new train_m;
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
        $this->setPageBtn(lang('show_title_trainc'), lang('show_title_lst_trainc'));
        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {          
            $data = input('post.');
            $result = $this->cModel->allowField(true)->validate(CONTROLLER_NAME.'.add')->save($data);
            if ($result) {
                write_log(lang('add_success_trainc'));
                return ajaxReturn(lang('add_success_trainc'), url('lst'));
            } else { 
                write_log(lang('add_error_trainc'));
                return ajaxReturn($this->cModel->getError());
            }
        }
        $this->setPageBtn(lang('show_title_trainc'), lang('show_title_add_trainc'));
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
                $save = array();
                (isset($data['name']) && !empty($data['name'])) &&   $save['name'] = trim($data['name']) ;
                (isset($data['technology']) && !empty($data['technology'])) &&   $save['technology'] = trim($data['technology']) ;
                (isset($data['state']) && !empty($data['state'])) &&   $save['state'] = trim($data['state']) ;
                (isset($data['img']) && !empty($data['img'])) &&   $save['img'] = trim($data['img']) ;
                (isset($data['seo_keyword']) && !empty($data['seo_keyword'])) &&   $save['seo_keyword'] = trim($data['seo_keyword']) ;
                (isset($data['seo_des']) && !empty($data['seo_des'])) &&   $save['seo_des'] = trim($data['seo_des']) ;
                (isset($data['order_key']) && !empty($data['order_key'])) &&   $save['order_key'] = trim($data['order_key']) ;

               $result = $this->cModel->allowField(true)->save($save, array('id'=>$data['id']));
                
            }
            if (FALSE !== $result) {
                write_log(lang('edit_success_trainc'));
                return ajaxReturn(lang('action_success'), url('lst')); 
            } else {
                write_log(lang('edit_error_trainc'));
                return ajaxReturn($this->cModel->getError());
            }
        } else {
            $this->setPageBtn(lang('show_title_trainc'), lang('show_title_edit_trainc'));
            $data = $this->cModel->get($id)->getData();
            $this->assign('data', $data);
            return $this->fetch();
        }
    }
    /**
     * [relation_f 关联文件]
     * @return [type] [description]
     */
    public function relation_f()
    {
        $file = new File_m;
        $where = [];                                                                                 
        if (input('get.search')) {
            $where['title|file_name'] = ['like', '%'.trim(input('get.search')).'%'];                  //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                       //默认顺序
        }

        $data = $file->where($where)->order($order)->paginate('', false, page_param());
        if (input('id')) {
            $traincat_id = intval(trim(input('id')));
            $data_f = db('traincat_file')->where(['traincat_id'=>$traincat_id])->column('file_id'); 
            $this->assign('data_f', $data_f);
        }    
        $this->assign('data',$data);
        $this->setPageBtn(lang('show_title_trainc'), lang('show_title_lst_file'));
        return $this->fetch();
    }

    /**
     * [relation_f 关联文件]
     * @return [type] [description]
     */
    public function relation_v()
    {
        $video = new Video_m;
        $where = [];                                                                                 
        if (input('get.search')) {
            $where['title|file_name'] = ['like', '%'.trim(input('get.search')).'%'];                  //搜索条件
        }
        if (input('get._sort')) {
            $order = explode(',', input('get._sort'));                                              //排序条件
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'order_key asc';                                                                       //默认顺序
        }
        $data = $video->where($where)->order($order)->paginate('', false, page_param());
        if (input('id')) {
            $traincat_id = intval(trim(input('id')));
            $data_f = db('traincat_video')->where(['traincat_id'=>$traincat_id])->column('video_id'); 
            $this->assign('data_f', $data_f);
        }    
        $this->assign('data',$data);
        $this->setPageBtn(lang('show_title_trainc'), lang('show_title_lst_video'));
        return $this->fetch();                                                                               
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
                        write_log(lang('del_success_trainc'));
                        return ajaxReturn(lang('action_success'), url('lst'));
                    } else {
                        write_log(lang('del_error_trainc'));
                        return ajaxReturn($this->cModel->getError());
                    }
                } catch (\Exception $e) {                   
                    write_log(lang('del_error_trainc'));                 // 回滚事务
                    return ajaxReturn($e->getMessage());
                }
            }
        }     
    }
}