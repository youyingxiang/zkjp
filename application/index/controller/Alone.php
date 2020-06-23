<?php
namespace app\index\controller;
use think\Controller;
use expand\Zkapi;
use think\Lang;
use think\Db;
class Alone extends Controller
{
	private $zkapi;
	public function _initialize()
	{
		parent::_initialize();
		$this->zkapi = new Zkapi;
	}
	public function get_city()
	{
		$province = trim(input('val'));
		if ($province) {
			//$data = json_decode($this->zkapi->get_city($id));
			//$data = $data->payload->results->dataList;

			$where = array(
				'province' => $province,
				'state' => '1'
			);

			$arr =  db('service_location')->where($where)->order('order_key asc') ->select() ;
			//$arr = json_decode(json_encode($arr),true);

			$data = array();
			foreach($arr as $k => $v){
				$row = array(
					'departName' => $v['city']
				);
					$data[$v['city']] = $row ;

			}

			$this->assign('city',$data);
			return $this->fetch();
		}
		
	}
	/**
	 * [login_state 登录状态]
	 * @return [type] [description]
	 */
	public function login_state()
	{
		if (check_user_login2()) {
			if (session('login_state_num') !== null && time() - session('login_state_num')['time'] < 600) {
				$num = session('login_state_num')['num'];
			} else {
				$response = json_decode($this->zkapi->notfinished_ticket(session('user_flag')),true);
				if ($response['code'] == '00000000') {
					$res = $response['payload']['results'];
					$num = intval($res['ticketNum']) + intval($res['customizationNum']) + intval($res['repairNum']);
					session('login_state_num',['num'=>$num,'time'=>time(),'ticketNum'=>$res['ticketNum'],'customizationNum'=>$res['customizationNum'],'repairNum'=>$res['repairNum']]);
				} else if ($response['code'] == 'ET000001') {
					session('user_flag',null);
					$num = 0;
				} else {
					$num = 0;
				}
			}
			return ajaxReturn(intval($num),1);
		} else {
			$url = !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
			return ajaxReturn($url);
		}
	}

	/**
	 * 数量
	 * @return [type] [description]
	 */
	public function get_num()
	{
		if (!empty(session('user_flag'))) {
			if (session('login_state_num') !== null && time() - session('login_state_num')['time'] < 600) {
				return ajaxReturn(['ticketNum'=>session('login_state_num')['ticketNum'],'customizationNum'=>session('login_state_num')['customizationNum'],'repairNum'=>session('login_state_num')['repairNum']],1);
			}
			$response = json_decode($this->zkapi->notfinished_ticket(session('user_flag')),true);
			if($response['code'] == '00000000'){
				return ajaxReturn($response['payload']['results'],1);
			} else {
				return ajaxReturn('Failed operation');
			}
		}
	}
	/**
	 * 点击次数
	 * @return [type] [description]
	 */
	public function click($id)
	{
		Db::name('News')->where('id', $id)->setInc('click');
		$click = Db::name('News')->where('id', $id)->value('click');
		?> 
		document.write(<?php echo $click;?>);
		<?php
	}
	/**
	 * 获取SN
	 * @author youxingxiang 
	 */
	public function get_search_sn()
	{
		$val = trim(input('val'));
		if ($val) {
         	$zkapi = new Zkapi;
        	$data = $zkapi->get_sn($val);

        	$data = iconv('GBK', 'UTF-8', $data);		
        	$data = htmlspecialchars_decode($data);
        	$data = json_decode($data);


        	if ($data->ResultCode == 1) {
				
				$data -> SNINFO -> OutDate = date("Y/m/d",strtotime($data -> SNINFO -> OutDate));
				$data -> SNINFO -> Area = str_replace("the",'',$data -> SNINFO -> Area) ;

        		$snData = $data->SNINFO;
        		$snData = json_decode(json_encode($snData),true);
        		$this->assign('snData',$snData);
        		return $this->fetch();
        	}
		}
	}
	//获取购物车数量
	public function get_cart_sum()
	{
		if (empty(session('user_flag'))) {									//用户没有登陆
            return ajaxReturn(lang('user_no_login'));
        } else {
        	$user_id = json_decode(cookie('user_info'))->id;   
	        $falg = $user_id.'-join_inquiry';               //用户ID+-join_inquiry 存储购物车
	        $cart = json_decode(cookie($falg),true);
	        if(empty($cart['sum_num']))return ajaxReturn(0,1);else return ajaxReturn($cart['sum_num'],1);
        }
	}
	/**
	 * [get_repair_branch 维修登记下拉城市选择分店]
	 * @return [type] [description]
	 */
	public function get_repair_branch()
	{
		if (empty(session('user_flag'))) {									//用户没有登陆
            return ajaxReturn(lang('user_no_login'));
        } else {
        	$zkapi = new Zkapi;
        	$data['sessionId'] = session('user_flag');
        	$data['cityName'] = input('val');     	
        	$results = json_decode($zkapi->get_repair_branch($data));
        	if ($results->code == '00000000') {
        		$results = !empty($results->payload->results->repairAddress)?$results->payload->results->repairAddress:'';
        		if (!empty($results)) {								//获取中控验证结果
	          		return ajaxReturn(lang('action_success'),1,1,$results);  
	          	} else {
	          		return ajaxReturn(lang('query_null'));
	          	}           		
	        } else {
	          	return ajaxReturn(lang('action_error'));
	        }       	
        }
	}
	/**
	 * 导入excel内容
	 * @return [type] [description]
	 */
    public function excel_in(){
        require_once('../extend/expand/ExcelReader.php');
        $e = new \ExcelReader;
        $url = trim(input('post.path'));
	//return ajaxReturn(substr($url,3));
        if (file_exists(".".substr($url,3))) {
        	$url = substr($url,4);
            $data = $e->reader_excel($url);	//获取excel 文件内容
            return ajaxReturn(json_encode($data),1);
        } else {
            return ajaxReturn(lang('path_no'));
        }   
    }
}
