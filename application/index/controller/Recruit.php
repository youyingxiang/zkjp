<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\admin\model\Recruit as recruit_m;
class Recruit extends Base
{
    private $cModel;   //当前控制器关联模型
    public function _initialize(){
        parent::_initialize();
        $this->cModel = new recruit_m;
    }
    public function index($id = 'a')
    {   
        if (in_array($id, ['a','b'])) {
            $where['type'] = ['like','%'.$id.'%'];
            $where['state'] = ['eq',1];
            if (input('get.job_type')) $where['job_id'] = ['eq',input('get.job_type')];                 //招聘点类别
            if (input('get.province')) $where['province'] = ['eq',input('get.province')];               //招聘点工作地点
            $data = $this->cModel->where($where)->order('order_key asc')->paginate('', false, page_param());
            $workAddress = $this->cModel->get_work_address($id);
            $job_ids = db('recruit')->where(['state'=>['eq',1],'type'=>['like','%'.$id.'%']])->order('order_key asc')->column('job_id');
            $job_ids = array_unique($job_ids);
            $job_type = $postData = Db::name('post')->where(['state'=>['eq',1],'id'=>['in',$job_ids]])->field('id,job_name')->order('order_key asc')->select();
            $this->setPageInfo(
                    Lang('recruit_'.$id),
                    $this->config_field['keywords']['v'], 
                    $this->config_field['description']['v'],
                    ['public','swiper.min','page','account','join']
                ); 
            $this->assign('recruitData',$data);
            $this->assign('workAddress',$workAddress);
            $this->assign('job_type',$job_type);
            $this->assign('r_type',Lang('recruit_'.$id));
            cache(request('get')->url(),$this->fetch());
            return $this->fetch();
        } else {
            abort(404,'查无记录');
        } 
    }
    public function get_search_recruit()
    {   
        if (input('job_type')) $where['job_id'] = ['like','%'.input('job_type').'%'];                 //招聘点类别
        if (input('province')) $where['province'] = ['like','%'.input('province').'%'];  
        if (input('val'))$where['name|province|city'] = ['like','%'.input('val').'%'];
        if (input('type_id'))$where['type'] = ['like','%'.input('type_id').'%'];
        $where['state'] = ['eq',1];
        $data = $this->cModel->where($where)->order('order_key asc')->select();
        $this->assign('recruitData',$data);
        return $this->fetch();
    }

    public function detail($id = 0)
    {
        if ($id) {
            $where['state'] = ['eq',1];
            $data = $this->cModel->where($where)->find($id);
            if (count($data)>0) {
                $this->assign('detailData',$data);
                $this->setPageInfo(
                    $data['name'],
                    $this->config_field['keywords']['v'], 
                    $this->config_field['description']['v'],
                    ['public','swiper.min','page','account','join']
                    );
                cache(request('get')->url(),$this->fetch());
                return $this->fetch();
            }
        }
        abort(404,'查无记录');
    }
}
