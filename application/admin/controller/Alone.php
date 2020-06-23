<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use expand\Zkapi;
use app\admin\model\Product as Product_m;
use app\admin\model\TrainCategory as traincat_m;
use app\admin\model\Cases as cases_m;
use app\admin\model\Scheme as scheme_m;
class Alone extends Controller
{
	public function _initialize()
    {
        parent::_initialize();
        if (!is_login()) {
            return ajaxReturn(lang('user_stop'));
            exit;
        }
    }  
    public function get_city($edit = false)
    {
        $id = empty(trim(input('val')))?'':trim(input('val'));  
        $zkapi = new Zkapi; 
        $data = json_decode($zkapi->get_city($id),true);
        $data = $data['payload']['results']['dataList'];
        $html = '';
        if ($data) {
            if ($edit == true) {
                    $html = $data;
            } else {
                foreach($data as $h) {
                    $html .= '<option cid="'.$h['id'].'" value="'.$h['departName'].'">'.$h['departName'].'</option>';
                }
            }
        }
        return $html;
    }
    /**
     * 培训 关联文件
     */
    public function relation_f_add_t()
    {
        $ids = input('post.ids');
        $traincat_id = input('post.traincat_id');
        db('traincat_file')->where(['traincat_id'=>$traincat_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $t = new traincat_m;      
        if (count($ids)>0) { 
            $res = $t->get($traincat_id)->traincatFile()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }

    }
    /**
     * 培训 关联视频
     */
    public function relation_v_add_t()
    {
        $ids = input('post.ids');
        $traincat_id = input('post.traincat_id');
        db('traincat_video')->where(['traincat_id'=>$traincat_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $t = new traincat_m;      
        if (count($ids)>0) { 
            $res = $t->get($traincat_id)->traincatVideo()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }

    }
    /**
     * 产品 关联文件
     */
    public function relation_f_add()
    {
        $ids = input('post.ids');
        $product_id = input('post.product_id');
        db('product_file')->where(['product_id'=>$product_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $p = new Product_m;      
        if (count($ids)>0) { 
            $res = $p->get($product_id)->productFile()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }

    }
    /**
     * 方案 关联文件
     */
    public function relation_f_add_s()
    {
        $ids = input('post.ids');
        $scheme_id = input('post.scheme_id');
        db('scheme_file')->where(['scheme_id'=>$scheme_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $s = new scheme_m;      
        if (count($ids)>0) { 
            $res = $s->get($scheme_id)->schemeFile()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }

    }
    /**
     * 产品 关联 产品
     */
    public function relation_p_add()
    {
        $ids = input('post.ids');
        $product_id = input('post.product_id');
        db('product_self')->where(['product_id_master'=>$product_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $p = new Product_m;      
        if (count($ids)>0) { 
            $res = $p->get($product_id)->productSelf()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }
    }
    /**
     * 案例 关联 产品
     */
    public function relation_p_add_c()
    {
        $ids = input('post.ids');
        $cases_id = input('post.cases_id');
        db('product_cases')->where(['cases_id'=>$cases_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $c = new cases_m;      
        if (count($ids)>0) { 
            $res = $c->get($cases_id)->casesProduct()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }
    }
    /**
     * 产品 关联 案例
     */
    public function relation_s_add()
    {
        $ids = input('post.ids');
        $product_id = input('post.product_id');
        db('product_scheme')->where(['product_id'=>$product_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $p = new Product_m;      
        if (count($ids)>0) { 
            $res = $p->get($product_id)->productScheme()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }
    }
    /**
     * 方案 关联 产品
     */
    public function relation_p_add_s()
    {
        $ids = input('post.ids');
        $scheme_id = input('post.scheme_id');
        db('product_scheme')->where(['scheme_id'=>$scheme_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $s = new scheme_m;      
        if (count($ids)>0) { 
            $res = $s->get($scheme_id)->schemeProduct()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }
    }
    /**
     * 产品 关联 案例
     */
    public function relation_c_add()
    {
        $ids = input('post.ids');
        $product_id = input('post.product_id');
        db('product_cases')->where(['product_id'=>$product_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $p = new Product_m;      
        if (count($ids)>0) { 
            $res = $p->get($product_id)->productCases()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }
    }
    /**
     * [relation_f_add_c 案例关联文件]
     * @return [type] [description]
     */
    public function relation_f_add_c()
    {
        $ids = input('post.ids');
        $cases_id = input('post.cases_id');
        db('cases_file')->where(['cases_id'=>$cases_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $c = new cases_m;      
        if (count($ids)>0) { 
            $res = $c->get($cases_id)->casesFile()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }

    }
    /**
     * 案例 关联 案例
     */
    public function relation_c_add_self()
    {
        $ids = input('post.ids');
        $cases_id = input('post.cases_id');
        db('cases_self')->where(['cases_id_master'=>$cases_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $c = new cases_m;      
        if (count($ids)>0) { 
            $res = $c->get($cases_id)->casesSelf()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }
    }


     /**
     * 方案 关联 案例
     */
    public function relation_c_add_s()
    {
        $ids = input('post.ids');
        $scheme_id = input('post.scheme_id');
        db('cases_scheme')->where(['scheme_id'=>$scheme_id])->delete();
        if (empty(trim($ids))) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        }
        $ids = explode('-', $ids);
        $s = new scheme_m;      
        if (count($ids)>0) { 
            $res = $s->get($scheme_id)->schemeCases()->saveAll($ids);
        }
        if (false !== $res) {
            write_log(lang('raction_success'));
            return ajaxReturn(lang('action_success'),1);
        } else {
            return ajaxReturn(lang('action_fail'));
        }
    }
}
