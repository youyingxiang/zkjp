<?php
use think\Session;
/**
 * [search_where 条件搜索]
 * @param  [type] $delparam [description]
 * @return [type]           [description]
 */
function search_where($delparam,$arr){
    $url_path = $_SERVER['PATH_INFO'];
    $get = input('get.');
    if( isset($get[$delparam]) ){ unset($get[$delparam]); }
    if( isset($get['page'])   ){ unset($get['page']);   }
    if($arr) {
        $paramStr = [];
        foreach ($get as $k=>$v){         
                $paramStr[] = $k.'='.$v;
        }
        foreach ($arr as $k1=>$v1){
                if ($v1 >= 0) {
                    $paramStr[] = $k1.'='.$v1;
                }                      
        }
        if ($paramStr) {
            $paramStrs = implode('&', $paramStr);
            $url_path = $url_path.'?'.$paramStrs;
        }
    }
    return $url_path;
}
function check_user_login()
{
    if (session('user_flag') !== null) {
        return session('user_flag');
    } else {
        empty(cookie('user_info'))?'':cookie('user_info',null);
        empty(session('login_state_num'))?'':session('login_state_num',null);
        empty(cookie('input_data'))?'':cookie('input_data',null);
        empty(cookie('input_datap'))?'':cookie('input_datap',null); 
        $url_ = request()->path();
        $url = url('/login?redirect_url=','',false).$url_;
        header("Location: $url");
        exit;
    }
}
//文本编辑器空格回车替换
function text_replace($str)
{
  $str = str_replace("\r","</br>",$str);
  $str = str_replace("\r\n","</br>",$str);
  $str = str_replace("\n","</br>",$str);
  // $str = str_replace(' ', "&nbsp", $str);
  return $str;
}
function check_user_login2()
{
    if (session('user_flag') !== null) {
        return session('user_flag');
    } else {
        return false;
    }
}

/**
 * [get_crumb 分类获取面包屑]
 * @param  [type] $first_name [第一个固定导航名称]
 * @param  [type] $first_url  [第一个固定导航url]
 * @param  [type] $data       [导航数组]
 * @return [type]             [html]
 */
function get_crumb($first_name,$first_url,$data=[])
{
    $html = '';
    if (count($data)>0) {                       //判断  有没有点击分类  就是全选的时候               
        $last = count($data)-1;                 //判断 是否就是数组就是一个
        if ($last>0) {
            foreach ($data as $key => $value) {
                if ($key == 0) {
                    $html .= '<div class="position add-position"><a href="'.$first_url.'">'.$first_name.'</a> >'; 
                    $html .= '<span><a href="'.$first_url.'/'.$value['id'].'">'.$value['name'];
                    if (count($value['child']) > 0) {
                      $html .= '<i>▼</i>';
                    }
                    $html .= '</a> > ';
                    if (count($value['child']) > 0) {
                        $html .= '<ul class="clearfix">';
                        foreach ($value['child'] as $kc => $vc) {
                          $html .= '<li><a href="'.$first_url.'/'.$vc['id'].'">'.$vc['name'].'</a></li>';
                        }
                        $html .= '</ul>';
                    }
                    $html .= '</span>';
                } else if ($key == $last) {
                    $html .= '<span><a href="javascript:void(0)">'.$value['name'];
                    if (count($value['child']) > 0) {
                      $html .= '<i>▼</i>';
                    }
                    $html .= '</a>';
                    if (count($value['child']) > 0) {
                        $html .= '<ul class="clearfix">';
                        foreach ($value['child'] as $kc => $vc) {
                          $html .= '<li><a href="'.$first_url.'/'.$vc['id'].'">'.$vc['name'].'</a></li>';
                        }
                        $html .= '</ul>';
                    }
                    $html .= '</span></div>';
                } else {
                    $html .= '<span><a href="'.$first_url.'/'.$value['id'].'">'.$value['name'];
                    if (count($value['child']) > 0) {
                      $html .= '<i>▼</i>';
                    }
                    $html .= '</a> > ';
                    if (count($value['child']) > 0) {
                        $html .= '<ul class="clearfix">';
                        foreach ($value['child'] as $kc => $vc) {
                          $html .= '<li><a href="'.$first_url.'/'.$vc['id'].'">'.$vc['name'].'</a></li>';
                        }
                        $html .= '</ul>';
                    }
                    $html .= '</span>';
                }
            }
        } else {
            $html .= '<div class="position add-position"><a href="'.$first_url.'">'.$first_name.'</a> >';
            $html .= '<span><a href="javascript:void(0)">'.$data[0]['name'];
            if (count($data[0]['child']) > 0) {
              $html .= '<i>▼</i>';
            }
            $html .= '</a>';
            if (count($data[0]['child']) > 0) {
                $html .= '<ul class="clearfix">';
                foreach ($data[0]['child'] as $kc => $vc) {
                  $html .= '<li><a href="'.$first_url.'/'.$vc['id'].'">'.$vc['name'].'</a></li>';
                }
                $html .= '</ul>';
            }
            $html .= '</span></div>';
        }
    } else {
        $html .= '<div class="position add-position"><a href="'.$first_url.'">'.$first_name.'</a></div>';
    }
    return $html;
}
/**
 * [get_crumb_product 产品详情获取面包屑]
 * @param  [type] $first_name [第一个固定导航名称]
 * @param  [type] $first_url  [第一个固定导航url]
 * @param  [type] $data       [导航数组]
 * @param  [type] $last_name  [导航最后一个名字]
 * @return [type]             [html]
 */
function get_crumb_product($first_name,$first_url,$data=[],$last_name)
{
    $html = '';
    if (count($data)>0) { 
        $last = count($data)-1;  
        foreach ($data as $key => $value) {
            if ($key == 0) {
                $html .= '<div class="position add-position"><a href="'.$first_url.'">'.$first_name.'</a> >'; 
                $html .= '<span><a href="'.url('/product_category/'.$value['id']).'">'.$value['name'];
                if (count($value['child']) > 0) {
                  $html .= '<i>▼</i>';
                }
                $html .= '</a> > ';
                if (count($value['child']) > 0) {
                    $html .= '<ul class="clearfix">';
                    foreach ($value['child'] as $kc => $vc) {
                      $html .= '<li><a href="'.url('/product_category/'.$vc['id']).'">'.$vc['name'].'</a></li>';
                    }
                    $html .= '</ul>';
                }
                $html .= '</span>';
            } else {
                $html .= '<span><a href="'.url('/product_category/'.$value['id']).'">'.$value['name'];
                if (count($value['child']) > 0) {
                  $html .= '<i>▼</i>';
                }
                $html .= '</a> > ';
                if (count($value['child']) > 0) {
                    $html .= '<ul class="clearfix">';
                    foreach ($value['child'] as $kc => $vc) {
                      $html .= '<li><a href="'.url('/product_category/'.$vc['id']).'">'.$vc['name'].'</a></li>';
                    }
                    $html .= '</ul>';
                }
                $html .= '</span>';
            }         
        }
        $html .= $last_name.'</div>';
    } else {
        $html .= '<div class="position add-position"><a href="'.$first_url.'">'.$first_name.'</a> >'.$last_name.'</div>';
    }
    return $html;
}
/**
 * [cookie_add_order 加入询价的时候 商品ID增加到cookie]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function cookie_add_order($id,$idnum,$flag)
{
    $user_id = json_decode(cookie('user_info'))->id;   
    $falg = $user_id.'-join_inquiry';           //用户ID+-join_inquiry 存储购物车
    if (empty(cookie($falg))) {
        $pids = [];
        $pids[] = $id;
        $idnums = [];
        $idnum_key = explode('-', $idnum)[0];   //key 为产品ID
        $idnum_value = explode('-', $idnum)[1]; //value 为产品数量
        $idnums[$idnum_key] = $idnum_value;
        $order_number = date('YmdHis').rand(1001,9999);//订单号
        $cart = ['order'=>$order_number,'pids'=>$pids,'idnums'=>$idnums];
        $cart['sum_num'] = intval($idnum_value); 
        cookie($falg,json_encode($cart));
    } else {
        $cart = json_decode(cookie($falg),true);
        $pids = $cart['pids'];
        $pids[] = $id;
        $idnums = $cart['idnums'];
        $idnum_key = explode('-', $idnum)[0];   //key 为产品ID
        $idnum_value = explode('-', $idnum)[1]; //value 为产品数量
        if ($flag == '+') {
          $_num = empty($idnums[$idnum_key])?0:$idnums[$idnum_key];
          $idnums[$idnum_key] = intval($idnum_value) + intval($_num);
        } else {
          $idnums[$idnum_key] = intval($idnum_value);
        }
        $pids = array_unique($pids);
        $cart['pids'] = $pids;
        $cart['idnums'] = $idnums;
        $sum = 0;
        foreach ($cart['idnums'] as $key => $value) {
            $sum += $value;
        }
        $cart['sum_num'] = $sum;    
        cookie($falg,json_encode($cart));
    }
}
//获取新闻下拉的面包屑的评级分类
function get_crumb_news($cateData,$news_cat)
{
    $html = '<span>';
    $html .= '<a href="'.url('/category_news/'.$cateData['id']).'">'.$cateData['name'].'<i>▼</i></a><ul class="clearfix">';
    foreach ($news_cat as $key => $value) {
      if ($value['id'] != $cateData['id']) {
        $html .= '<li><a href="'.url('/category_news/'.$value['id']).'">'.$value['name'].'</a></li>';
      }
    }
    $html .= '</ul></span';
    return $html;
}
/**
 * [json_zkpai 解析中控的json数据]
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function json_zkpai($str)
{
    $str = "[".substr($str, 0, -1)."]";
    $str = str_replace("'",'"',$str);
    return json_decode($str,true);
}
/**
 * [visitors 统计访问人数]
 * @return [type] [description]
 */
function visitors()
{
    if (empty(cache('visitors'))) {
        $data['num'] = 1;
        $data['date'] = date('Y-m-d');
        cache('visitors',$data);
    } else {
        $data = cache('visitors');
        if ($data['date'] == date('Y-m-d')) {
            $old_num = intval($data['num']);
            $old_num ++;
            $data['num'] = $old_num;
        } else {
            db('visitors')->insert($data);
            $data['num'] = 1;
            $data['date'] = date('Y-m-d');
        }
        cache('visitors',$data);
    }
}
/**
 * [get_img_url 获取图片Url]
 * @param  [type] $url [description]
 * @return [type]      [description]
 */
function get_img_url($url)
{
  $url = empty($url)?"javascript:void(0)":$url;
  echo $url;
}
/**
 * [check_target 判断是否跳转外链]
 * @param  [type] $url [description]
 * @return [type]      [description]
 */
function check_target($url)
{
  if (preg_match("/^(http:\/\/|https:\/\/).*$/",$url)) {
      echo "target='_blank'";
  }
}
/**
 * [RemoveXSS 过滤脚本攻击]
 * @param [type] $val [被过滤的参数]
 */
function RemoveXSS($val) {  
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed  
   // this prevents some character re-spacing such as <java\0script>  
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs  
   $val = preg_replace('/([\x00-\x08|\x0b-\x0c|\x0e-\x19])/', '', $val);  

   // straight replacements, the user should never need these since they're normal characters  
   // this prevents like <IMG SRC=@avascript:alert('XSS')>  
   $search = 'abcdefghijklmnopqrstuvwxyz'; 
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';  
   $search .= '1234567890!@#$%^&*()'; 
   $search .= '~`";:?+/={}[]-_|\'\\'; 
   for ($i = 0; $i < strlen($search); $i++) { 
      // ;? matches the ;, which is optional 
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 

      // @ @ search for the hex values 
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ; 
      // @ @ 0{0,7} matches '0' zero to seven times  
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ; 
   } 

   // now the only remaining whitespace attacks are \t, \n, and \r 
   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base'); 
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'); 
   $ra = array_merge($ra1, $ra2); 

   $found = true; // keep replacing as long as the previous round replaced something 
   while ($found == true) { 
      $val_before = $val; 
      for ($i = 0; $i < sizeof($ra); $i++) { 
         $pattern = '/'; 
         for ($j = 0; $j < strlen($ra[$i]); $j++) { 
            if ($j > 0) { 
               $pattern .= '(';  
               $pattern .= '(&#[xX]0{0,8}([9ab]);)'; 
               $pattern .= '|';  
               $pattern .= '|(&#0{0,8}([9|10|13]);)'; 
               $pattern .= ')*'; 
            } 
            $pattern .= $ra[$i][$j]; 
         } 
         $pattern .= '/i';  
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag  
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags  
         if ($val_before == $val) {  
            // no replacements were made, so exit the loop  
            $found = false;  
         }  
      }  
   }  
   return $val;  
}   
