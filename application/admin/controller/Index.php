<?php
namespace app\admin\controller;

use think\Controller;
use think\Lang;
use think\Cache;

class Index extends Base
{   
    public function lst()
    {
        $userTotal = $this->userTotal();                    //用户总数
        $this->assign('userTotal', $userTotal);
        
        
        // $archiveTotal = $this->archiveTotal();              //文章总数
        // $this->assign('archiveTotal', $archiveTotal);
        
        $userNear = $this->userNear();                      //最近7天注册用户数
        $this->assign('userNear', $userNear);

        // $guestbookTotal = $this->guestbookTotal();          //最近7天评论数
        // $this->assign('guestbookTotal', $guestbookTotal);
        
        $loginLogList = $this->loginLogList();              //最新登录8条信息
        $this->assign('loginLogList', $loginLogList);     
        
        $loginLogLineJson = $this->loginLogLineJson();      //最近30天访问统计json
        $this->assign('loginLogLineJson', $loginLogLineJson);
        
        // $groupPieJson = $this->groupPieJson();              //用户组人数统计
        // $this->assign('groupPieJson', $groupPieJson);
        
        $systemConfig = $this->systemConfig();              //服务器信息配置
        $this->assign('systemConfig', $systemConfig);
        $this->setPageBtn(lang('show_title_index'), lang('show_title_index_lst'));
        
        return $this->fetch();
    }
    /**
     * @Title: userTotal
     * @Description: todo(用户总数)
     * @author 游兴祥
     * @date 2017年8月14日
     * @throws
     */
    private function userTotal()
    {
        $userModel = new \app\admin\model\Admin;
        return $userTotal = $userModel->count();
    }

    
    /**
     * @Title: userNear
     * @Description: todo(最近7天注册用户)
     * @author 游兴祥
     * @date 2017年8月14日
     * @throws
     */
    private function userNear()
    {
        $userModel = new \app\admin\model\Admin;
        $nowTime = strtotime(date('Ymd', time())) + 86400;
        $userTime = $nowTime - 604800;
        return $userNear = $userModel->where('add_time', 'egt', $userTime)->count();
    }
    
    /**
     * @Title: archiveTotal
     * @Description: todo(文章总数)
     * @author 游兴祥
     * @date 2017年8月14日
     * @throws
     */
    private function archiveTotal()
    {
        $archiveModel = new \app\admin\model\Archive;
        return $archiveTotal = $archiveModel->count();
    }
    
    /**
     * @Title: guestbookTotal
     * @Description: todo(最近7天评论数)
     * @author 游兴祥
     * @date 2017年8月14日
     * @throws
     */
    private function guestbookTotal()
    {
        $guestbookModel = new \app\admin\model\Guestbook;
        $nowTime = strtotime(date('Ymd', time())) + 86400;
        $guestbookTime = $nowTime - 604800;
        return $guestbookTotal = $guestbookModel->where('create_time', 'egt', $guestbookTime)->count();
    }
    
    /**
     * @Title: loginLogList
     * @Description: todo(最新登录8条信息)
     * @author 游兴祥
     * @date 2017年8月14日
     * @throws
     */
    private function loginLogList()
    {
        $loginLogModel = new \app\admin\model\Log;
        return $loginLogList = $loginLogModel->where('log_type','login')->limit(8)->order('id desc')->select();
    }
    
    /**
     * @Title: loginLogLineJson
     * @Description: todo(最近30天登录统计json)
     * @author 游兴祥
     * @date 2017年8月14日
     * @throws
     */
    public function loginLogLineJson()
    {
        $userModel = new \app\admin\model\Admin;
        
        $nowTime = date('Y-m-d');
        $loginLogLineTime = date('Y-m-d',time() - 2592000);
        $loginLogLine = db('visitors')->where(['date' => ['between', [$loginLogLineTime, $nowTime]]])->select();
        $loginLogLineArr = [];
        $loginLogLineArr['datasets'][0]['label'] = lang('login_count');
        $loginLogLineArr['datasets'][0]['fill'] = false;
        $loginLogLineArr['datasets'][0]['borderColor'] = '#4bc0c0';
        $loginLogLineArr['labels'] = [];
        $loginLogLineArr['datasets']['data']= [];
        foreach ($loginLogLine as $k=>$v){
            $labels = $v['date'];
            if (!in_array($labels, $loginLogLineArr['labels'])) {
                $loginLogLineArr['labels'][] = $v['date'];
            }
            $loginLogLineArr['datasets']['data'][$labels] = $v['num'];
        }
        $old = $loginLogLineArr['datasets']['data'];
        unset($loginLogLineArr['datasets']['data']);
        foreach ($old as $v){
            $loginLogLineArr['datasets'][0]['data'][] = $v;
        }
        return $loginLogLineJson = json_encode($loginLogLineArr);
    }
    
    /**
     * @Title: groupPieJson
     * @Description: todo(用户组人数统计)
     * @author 游兴祥
     * @date 2017年8月14日
     * @throws
     */
    private function groupPieJson()
    {
        $agModel = new \app\admin\model\Role;
        $userModel = new \app\admin\model\admin;
        
        $groupPieArr = [];
        
        $agData = $agModel->where('status', 1)->select();
        $noGroup = ['id' => '0', 'title' => '无角色用户', 'pic' => '#666'];
        $agData[count($array)-1] = $noGroup;
        foreach($agData as $k => $v){
            $agData[$k]['count'] = 0;
            $groupPieArr['labels'][] = $v['role_name'];
        }
        $userData = $userModel->select();
        foreach ($userData as $k=>$v){
            $userGroup = $v->userGroup;
            if(!empty($userGroup)){
                foreach ($userGroup as $k2 => $v2){
                    foreach ($agData as $k3 =>$v3){
                        if ($v3['id'] == $v2['group_id']){
                            $agData[$k3]['count'] += 1;
                            break;
                        }
                    }
                }
            }else{
                $agData[count($array)-1]['count'] += 1;
            }
        }
        foreach($agData as $k=>$v){
            $groupPieArr['datasets'][0]['data'][] = $v['count'];
            $groupPieArr['datasets'][0]['backgroundColor'][] = $v['pic'];
        }
        return $groupPieJson = json_encode($groupPieArr);
    }
    
    /**
     * @Title: systemConfig
     * @Description: todo(服务器信息配置)
     * @author 游兴祥
     * @date 2017年8月14日
     * @throws
     */
    private function systemConfig()
    {
        return $config = [
            lang('operating_system') => PHP_OS,
            lang('server_time') => date("Y-n-j H:i:s"),
            lang('running_environment') => $_SERVER["SERVER_SOFTWARE"],
            lang('operation_mode') => php_sapi_name(),
            lang('upload_max_filesize') => ini_get('upload_max_filesize'),
            lang('max_execution_time') => ini_get('max_execution_time'),
        ];
    }
    
    
    /**
     * @Title: cleanCache
     * @Description: todo(清楚缓存)
     * @author 游兴祥
     * @date 2017年8月14日
     * @throws
     */
    public function cleanCache()
    {
        if (request()->isPost()){
            deldir(RUNTIME_PATH);
            Cache::clear(); 
            return ajaxReturn(lang('action_success'));
        }else{
            deldir(RUNTIME_PATH);
            Cache::clear(); 
            return $this->fetch();
        }
    }
}
