<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Aboutus extends Base
{
	/**
	 * [index 关于我们]
	 */
	public function index()
	{
		$this->setPageInfo(
                lang('about_us'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [develop 发展历程]
	 */
	public function develop()
	{	
		$sql = "SELECT DISTINCT(date_format(develop_time,'%Y')) AS "
			."nianfen FROM ".config('database.prefix')."develop ORDER BY nianfen DESC"; 
		$year = Db::name('develop')->query($sql);
		$this->setPageInfo(
                lang('develop'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','layui','layer/layer']
            );
		$this->assign('year',$year);
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [get_develop_content ajax获取列表内容]
	 * @return [type] [html]
	 */
	public function get_develop_lst()
	{	
		$year = input('year');
		$start_time = $year.'-01-01';
		$end_time = $year.'-12-31';
		$data = Db::name('develop')->where(['develop_time'=>[['>=',$start_time],['<=',$end_time],'and'],'state'=>['eq',1]])
								   ->order('develop_time desc')
		                           ->select();
		$this->assign('data',$data);
		return $this->fetch();
	}
	/**
	 * [culture 中控文化]
	 */
	public function culture()
	{
		$this->setPageInfo(
                lang('culture'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [team 专家团队]
	 * @return [type] [description]
	 */
	public function team()
	{	
		$type = config('team_cat');
		$this->setPageInfo(
                lang('team'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		$res = Db::name('team')->where(['state'=>['eq',1]])->order('order_key asc')->select();
		$data = [];
		foreach ($res as $key => $value) {
			$data[$value['typeid']][] = $value;
		}
		$this->assign('type',$type);
		$this->assign('data',$data);
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [company_profile 公司简介]
	 */
	public function company_profile()
	{	
		$this->setPageInfo(
                lang('company_profile'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [win_win 合作共赢]
	 */
	public function win_win()
	{
		$this->setPageInfo(
                lang('win_win'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [core_technology 核心技术]
	 */
	public function core_technology()
	{
		$this->setPageInfo(
                lang('core_technology'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [honor 荣誉资质]
	 */
	public function honor()
	{
		$this->setPageInfo(
                lang('honor'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * quality_manage 质量管理
	 */
	public function quality_manage()
	{
		$this->setPageInfo(
                lang('quality_manage'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [duty 社会责任]
	 * @return [type] [description]
	 */
	public function duty()
	{
		$dutyCateData = db('duty_category')
							->field('id,name,icon,img')
							->where(['state'=>['eq',1]])
						    ->order('order_key asc')
					        ->select();
		$this->assign('dutyCateData',$dutyCateData);
		$this->setPageInfo(
                lang('duty'),
                $this->config_field['keywords']['v'], 
                $this->config_field['description']['v'],
                ['public','swiper.min','page02','center','page'],
                ['jquery.min','swiper.min','page','layui','layer/layer']
            );
		cache(request('get')->url(),$this->fetch());
		return $this->fetch();
	}
	/**
	 * [category_duty 社会责任列表]
	 * @return [type] [description]
	 */
	public function category_duty($id = '')
	{
		$id = input('id');
		if ($id) {
			$typeInfo = db('duty_category')->field('seo_keyword,seo_des,name,banner,content')->where(['state'=>['eq',1]])->find($id);
			$this->assign('typeInfo',$typeInfo);
			if (count($typeInfo)>0) {
				$data = db('duty')
				    ->field('id,title,img,abstract')
					->where(['state'=>['eq',1],'typeid'=>['eq',$id]])
				    ->order('id desc')
			        ->select();
			    $this->assign('typeData',$data);
			    $this->setPageInfo(
	                $typeInfo['name'],
	                $typeInfo['seo_keyword'], 
	                $typeInfo['seo_des'],
	                ['public','swiper.min','page','center'],
	                ['jquery.min','swiper.min','page','layui','layer/layer']
	            );
	            cache(request('get')->url(),$this->fetch());
			    return $this->fetch();
			}
		}
		abort(404,'查无记录');
	}
	/**
	 * [duty_detail 社会责任详情]
	 * @param  string $id [description]
	 * @return [type]     [description]
	 */
	public function duty_detail($id = ''){
		if  ($id) {
            $where['state'] = ['eq',1];
            $data = db('duty')->field('content,title,seo_des,seo_keyword,banner')->where($where)->find($id);
            if (count($data)>0) {
            	$next = db('duty')->field('id,title')->where(['state'=>['eq',1],'id'=>['gt',$id]])->order('id asc')->find();
            	$prev = db('duty')->field('id,title')->where(['state'=>['eq',1],'id'=>['lt',$id]])->order('id desc')->find();
            	$this->assign('next',$next);
            	$this->assign('prev',$prev);
                $this->assign('detailData',$data);
                $this->setPageInfo(
                    $data['title'],
                    $data['seo_keyword'], 
                    $data['seo_des'],
                    ['public','swiper.min','page','page02','center'],
                    ['jquery.min','swiper.min','page','layui','layer/layer']
                    );
                cache(request('get')->url(),$this->fetch());
                return $this->fetch();
            }
        }
        abort(404,'查无记录');
	}
}