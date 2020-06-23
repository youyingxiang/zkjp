<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author:youxingxiang
// +----------------------------------------------------------------------


use think\Route;
Route::rule('auth_online_shop','index/Index/auth_online_shop');						//授权网店
Route::rule('search','index/Index/search');											//站内搜索
Route::get('recruit_type/[:id]','index/Recruit/index');								//招聘类别
Route::get('recruit_detail/[:id]','index/Recruit/detail');							//招聘内容详情
Route::get('register','index/User/register');										//注册第一个界面
Route::rule('login','index/User/login');											//登录
Route::rule('register_userinfo','index/User/register_userinfo');					//注册完善信息
Route::rule('logout','index/User/logout');											//退出
Route::rule('login_state','index/Alone/login_state');								//登录状态
Route::get('download_catgory/[:id]','index/Service/category');						//下载分类
Route::rule('security_check','index/Service/security_check');						//检查SN
Route::rule('product_warranty','index/Service/product_warranty');					//产品保修
Route::rule('category_news/[:id]','index/News/lst');								//新闻分类
Route::rule('news_detail/[:id]','index/News/detail');								//新闻详情
Route::rule('subscribe','index/News/subscribe');									//订阅
Route::rule('news_center','index/News/news_center');								//新闻中心
Route::rule('user_center','index/User/center');										//用户中心
Route::rule('feedback','index/User/feedback');										//意见反馈
Route::rule('authentication','index/User/authentication');							//权限认证
Route::rule('apply_pwdback','index/User/apply_pwdback');							//找回设备管理员密码
Route::rule('apply_gold_vip','index/User/apply_gold_vip');							//申请成为黄金会员
Route::rule('user_info','index/User/user_info');									//完善个人信息
Route::rule('zk_repair_detail/[:doccode]','index/User/zk_repair_detail');			//维修订单详情
Route::rule('ticket_query','index/User/ticket_query');								//工单查询
Route::rule('zk_ticket_detail/[:someId]','index/User/zk_ticket_detail');		    //工单详细
Route::rule('reservation_query','index/User/reservation_query');					//预约查询
Route::rule('zk_reserv_detail/[:orderNumber]','index/User/zk_reservation_detail');	//预约详细

Route::rule('customized_query','index/User/customized');							//定制查询
Route::rule('zk_customized_detail/[:doccode]','index/User/zk_customized_detail');	//定制查询详细




Route::rule('repair_query','index/User/repair_query');								//维修查询
Route::rule('password_retrieval','index/User/password_retrieval');					//找回密码
Route::rule('update_password','index/User/update_password');						//修改密码
Route::rule('zk_faq','index/Service/zk_faq');										//常见问题
Route::rule('service','index/Service/index');										//服务支持
Route::rule('reservation','index/Service/reservation');								//预约上门服务
Route::rule('ticket_save','index/Service/ticket_save');								//提交工单
Route::rule('online_support','index/Service/online_support');						//在线支持
Route::rule('download_history','index/Service/download_history');					//下载记录


Route::rule('train_center','index/Service/train_center');	
Route::rule('video_cat/[:id]','index/Service/video_cat');							//培训视频
Route::rule('file_cat/[:id]','index/Service/file_cat');							    //培训文件
Route::rule('teacher','index/Service/teacher');							    		//培训文件
Route::rule('technology_cat/[:id]','index/Service/technology_cat');					//培训技术指导
Route::rule('apply_training','index/Service/apply_training');
Route::rule('course_lst','index/Service/course_lst');								//网络课程
Route::rule('course_detail/[:id]','index/Service/course_detail');					//网络课程详情
Route::rule('certificate','index/Service/certificate');								//认证及流程





Route::rule('repair_register','index/Service/repair_register_step2');	
Route::rule('aboutus','index/Aboutus/index');										//关于我们	
Route::rule('develop','index/Aboutus/develop');										//发展历程
Route::rule('culture','index/Aboutus/culture');										//中控文化	
Route::rule('team','index/Aboutus/team');											//中控团队
Route::rule('duty','index/Aboutus/duty');											//社会责任
Route::rule('category_duty/[:id]','index/Aboutus/category_duty');				    //社会责任列表
Route::rule('duty_detail/[:id]','index/Aboutus/duty_detail');						//社会责任详情


Route::rule('company_profile','index/Aboutus/company_profile');						//公司简介
Route::rule('win_win','index/Aboutus/win_win');										//合作共赢
Route::rule('core_technology','index/Aboutus/core_technology');						//核心技术
Route::rule('honor','index/Aboutus/honor');											//荣誉资质
Route::rule('quality_manage','index/Aboutus/quality_manage');						//质量管理
Route::rule('partner','index/Partner/index');										//合作伙伴1
Route::rule('partner_benefit','index/Partner/partner_benefit');						//合作伙伴好处
Route::rule('partner_introduce','index/Partner/partner_introduce');					//合作伙伴介绍
Route::rule('partner_step1','index/Partner/partner_step1');							//成为合作伙伴第一步
Route::rule('partner_step2','index/Partner/partner_step2');							//成为合作伙伴第二步
Route::rule('product_category/[:id]','index/Product/product_category');			    //产品分类
Route::rule('product_detail/[:id]','index/Product/detail');			    			//产品详情
Route::rule('order_step1/[:id]','index/Product/order_step1');						//产品核实订单1
Route::rule('order_step2','index/Product/order_step2');								//产品核实订单2		

Route::rule('cases_detail/[:id]','index/Solution/cases_detail');					//案例详情
Route::rule('cases_lst','index/Solution/cases_lst');								//案例列表
Route::rule('scheme_lst','index/Solution/scheme_lst');									//案例列表
Route::rule('scheme_detail/[:id]','index/Solution/scheme_detail');					//方案详情
Route::rule('project_consultation','index/Solution/project_consultation');			//项目咨询
Route::rule('contactus','index/Contactus/index');									//联系我们
Route::rule('address','index/Contactus/headquarters_addresss');						//总部地址
Route::rule('sale_map','index/Contactus/sale_map');									//销售地图
Route::rule('service_location','index/Contactus/service_location');					//售后站点
Route::rule('zhongkong_backstage_login','admin/Login/login');						//后台登录


Route::rule('web_site_map','index/Index/web_site_map');							     //网站地图
// Route::get('download/:id','index/Service/category'));
// Route::get('test2/:id','sample/test/test2');
// Route::get('hellow/zhangshan','index/Hellow/index');
