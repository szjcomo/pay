<?php
/**
* |-----------------------------------------------------------------------------------
* copyright (c) 2014-2018, http://www.sizhijie.com. All Rights Reserved.
* Website: www.sizhijie.com
* Version: 思智捷信息科技有限公司
* author : szjcomo 
* |-----------------------------------------------------------------------------------
*/

namespace szjcomo\pay;

/**
 * 微信支付包
 */
class WechatPay
{
	/**
	 * [$UnifiedOrderUrl 统一下单接口]
	 * @var string
	 */
	protected static $UnifiedOrderURL 	= 'https://api.mch.weixin.qq.com/pay/unifiedorder';
	/**
	 * [$OrderQueryURL 订单查询接口]
	 * @var string
	 */
	protected static $OrderQueryURL 	= 'https://api.mch.weixin.qq.com/pay/orderquery';
	/**
	 * [$CloseOrderURL 关闭订单接口]
	 * @var string
	 */
	protected static $CloseOrderURL 	= 'https://api.mch.weixin.qq.com/pay/closeorder';
	/**
	 * [$RefundOrderURL 订单退款接口]
	 * @var string
	 */
	protected static $RefundOrderURL	= 'https://api.mch.weixin.qq.com/secapi/pay/refund';
	/**
	 * [$RefundQueryURL 查询退款接口]
	 * @var string
	 */
	protected static $RefundQueryURL	= 'https://api.mch.weixin.qq.com/pay/refundquery';
	/**
	 * [$DownloadBillURL 下载对账单接口]
	 * @var string
	 */
	protected static $DownloadBillURL   = 'https://api.mch.weixin.qq.com/pay/downloadbill';
	/**
	 * [$MicropayURL 提交被扫支付接口]
	 * @var string
	 */
	protected static $MicroPayURL 		= 'https://api.mch.weixin.qq.com/pay/micropay';
	/**
	 * [$ReverseOrderUrl 交易保障接口]
	 * @var string
	 */
	protected static $ReverseOrderURL	= 'https://api.mch.weixin.qq.com/secapi/pay/reverse';
	/**
	 * [$SendRedBagsURL 发放现金红包接口]
	 * @var string
	 */
	protected static $SendRedBagsURL    = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
	/**
	 * [$TransfersURL 企业付款给用户零钱]
	 * @var string
	 */
	protected static $TransfersURL		= 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
	/**
	 * [$QueryTransInfoURL 查询企业付款给用户的信息接口]
	 * @var string
	 */
	protected static $QueryTransInfoURL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';

	/**
	 * [$options 参数列表]
	 * @var array
	 */
	protected static $options = [];
	/**
	 * [resultHandler 返回值处理函数]
	 * @author 	   szjcomo
	 * @createTime 2020-01-02
	 * @param      [type]     $result [description]
	 * @return     [type]             [description]
	 */
	protected static function resultHandler($result)
	{
		$arr = self::xmlToArray($result);
		if(isset($arr['err_code'])) return self::appResult($arr['err_code_des'],$arr);
		return self::appResult('SUCCESS',$arr,false,0);
	}


	/**
	 * [unifiedOrder 统一下单]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data [description]
	 * @return    [type]           [description]
	 */
	public static function unifiedOrder(array $data = [],$defaultFields = [])
	{
		if(empty($data)) throw new \Exception("请传入支付参数");
		try{
			$options = ['time_start','time_expire','nonce_str','trade_type'];
			$xml = self::getPostBaseData($data,array_merge($options,$defaultFields));
			$result = self::curlRequset(self::$UnifiedOrderURL,'post',$xml);
			return self::resultHandler($result);
		} catch(\Exception $err){
			throw $err;
		}
	}

	/**
	 * [closeOrder 关闭订单]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data [description]
	 */
	public static function closeOrder($data = [],$defaultFields = [])
	{
		if(empty($data)) throw new \Exception("请传入支付参数", 1000004);
		try{
			$options = ['nonce_str'];
			$result = self::curlRequset(self::$CloseOrderURL,'post',self::getPostBaseData($data,array_merge($options,$defaultFields)));
			return self::resultHandler($result);
		} catch(\Exception $err){
			throw $err;
		}
	}

	/**
	 * [queryOrder 查询订单]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data          [description]
	 * @param     array      $defaultFields [description]
	 */
	public static function queryOrder($data = [],$defaultFields = [])
	{
		if(empty($data)) throw new \Exception("请传入支付参数", 1000004);
		try{
			$options = ['nonce_str'];
			$result = self::curlRequset(self::$OrderQueryURL,'post',self::getPostBaseData($data,array_merge($options,$defaultFields)));
			return self::resultHandler($result);
		} catch(\Exception $err){
			throw $err;
		}
	}
	/**
	 * [billOrder 下载对账单]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data          [description]
	 * @param     array      $defaultFields [description]
	 */
	public static function billOrder($data = [],$defaultFields = [])
	{
		if(empty($data)) throw new \Exception("请传入支付参数", 1000004);
		try{
			$options = ['bill_date','nonce_str','bill_type'];
			$result = self::curlRequset(self::$DownloadBillURL,'post',self::getPostBaseData($data,array_merge($options,$defaultFields)));
			$tmp = self::xmlToArray($result);
			if($tmp == false) $tmp = array_filter(explode("\n",$result));
			return self::appResult('SUCCESS',$tmp,false);
		} catch(\Exception $err){
			throw $err;
		}
	}
	/**
	 * [refundOrder 申请订单退款]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data          [description]
	 * @param     string     $cert          [description]
	 * @param     string     $key           [description]
	 * @param     array      $defaultFields [description]
	 */
	public static function refundOrder($data = [],$cert = '',$key = '',$defaultFields = [])
	{
		if(empty($data)) throw new \Exception("请传入支付参数", 1000004);
		if(empty($cert)) throw new \Exception('请传入您的商户证书',1000005);
		if(empty($key)) throw new \Exception('请传入您的商户证书',1000006);
		try{
			$options = ['out_refund_no','nonce_str'];
			$result = self::curlPostSsl(self::$RefundOrderURL,self::getPostBaseData($data,array_merge($options,$defaultFields)),$cert,$key);
			return self::resultHandler($result);
		} catch(\Exception $err){
			throw $err;
		}
	}
	/**
	 * [refundQuery 订单退款查询]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data          [description]
	 * @param     array      $defaultFields [description]
	 */
	public static function refundQuery($data = [],$defaultFields = [])
	{
		if(empty($data)) throw new \Exception("请传入支付参数", 1000004);
		try{
			$options = ['nonce_str'];
			$result = self::curlRequset(self::$RefundQueryURL,'post',self::getPostBaseData($data,array_merge($options,$defaultFields)));
			return self::resultHandler($result);
		} catch(\Exception $err){
			throw $err;
		}
	}
	/**
	 * [sendRedBags 发放现金红包]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data          [description]
	 * @param     [type]     $cert          [description]
	 * @param     string     $key           [description]
	 * @param     array      $defaultFields [description]
	 */
	public static function sendRedBags($data = [],$cert = null,$key = '',$defaultFields = [])
	{
		if(empty($data)) throw new \Exception("请传入支付参数", 1000004);
		if(empty($cert)) throw new \Exception('请传入您的商户证书',1000005);
		if(empty($key)) throw new \Exception('请传入您的商户证书',1000006);
		try{
			$options = ['wishing','nonce_str','act_name','remark','scene_id'];
			$result = self::curlPostSsl(self::$SendRedBagsURL,self::getPostBaseData($data,array_merge($options,$defaultFields)),$cert,$key);
			return self::resultHandler($result);
		} catch(\Exception $err){
			throw $err;
		}
	}
	/**
	 * [payUserWallet 企业付款给用户零钱]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data          [description]
	 * @param     string     $cert          [description]
	 * @param     string     $key           [description]
	 * @param     array      $defaultFields [description]
	 */
	public static function payUserWallet($data = [],$cert = '',$key = '',$defaultFields = [])
	{
		if(empty($data)) throw new \Exception("请传入支付参数", 1000004);
		if(empty($cert)) throw new \Exception('请传入您的商户证书',1000005);
		if(empty($key)) throw new \Exception('请传入您的商户证书',1000006);
		try{
			$options = ['nonce_str','check_name'];
			$result = self::curlPostSsl(self::$TransfersURL,self::getPostBaseData($data,array_merge($options,$defaultFields)),$cert,$key);
			return self::resultHandler($result);
		} catch(\Exception $err){
			throw $err;
		}
	}
	/**
	 * [queryPayUser 查询企业付款给用户零钱的信息接口]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data          [description]
	 * @param     string     $cert          [description]
	 * @param     string     $key           [description]
	 * @param     array      $defaultFields [description]
	 */
	public static function queryPayUser($data = [],$cert = '',$key = '',$defaultFields = [])
	{
		if(empty($data)) throw new \Exception("请传入支付参数", 1000004);
		if(empty($cert)) throw new \Exception('请传入您的商户证书',1000005);
		if(empty($key)) throw new \Exception('请传入您的商户证书',1000006);
		try{
			$options = ['nonce_str'];
			$result = self::curlPostSsl(self::$QueryTransInfoURL,self::getPostBaseData($data,array_merge($options,$defaultFields)),$cert,$key);
			return self::resultHandler($result);
		} catch(\Exception $err){
			throw $err;
		}
	}
	/**
	 * [jsPayOptions 生成JSapi支付参数]
	 * @author    como
	 * @datetime  2019-04-05
	 * @version   [1.5.0]
	 * @param     array      $data [description]
	 */
	public static function jsPayOptions($data = [],$options = [])
	{
		if(empty($data)) return appResult('参数不能为空');
		$tmpData = [];
		if(empty($options)){
			$callback = function($val,$key) use(&$tmpData){
				$tmpData[strtolower($key)] = $val;
			};
			array_walk($options,$callback);			
		}
		try{
			if(isset($data['return_code']) && isset($data['result_code']) && $data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS'){
				$baseOption = array_merge($data,$tmpData);
				$params = [
					'appId'=>$baseOption['appid'],
					'nonceStr'=>self::getRandStr(),
					'timeStamp'=>szjTime(),
					'signType'=>'MD5',
					'package'=>'prepay_id='.$baseOption['prepay_id'],
					'key'=>$baseOption['key']
				];
				$signPackage = self::makeSign($params);
				array_pop($params);
				$params['paySign'] = $signPackage;
				return self::appResult('SUCCESS',$params,false);
			} else {
				return self::appResult($data['return_msg'],$data);
			}			
		} catch(\Exception $err){
			return appResult($err->getMessage());
		}
	}
	/**
	 * [reply_callback 微信支付回调回复快捷方式]
	 * @author 	   szjcomo
	 * @createTime 2020-01-10
	 * @param      array      $data [description]
	 * @return     [type]           [description]
	 */
	public function reply_callback(array $data)
	{

	}

	/**
	 * [appResult 全局统一返回函数]
	 * @author 	   szjcomo
	 * @createTime 2019-11-18
	 * @param      string       $info [description]
	 * @param      [type]       $data [description]
	 * @param      bool|boolean $err  [description]
	 * @param      int|integer  $code [description]
	 * @return     [type]             [description]
	 */
	public static function appResult(string $info,$data = null,bool $err = true,int $code = 0)
	{
		return ['info'=>$info,'data'=>$data,'err'=>$err,'code'=>$code];
	}


	/**
	 * [getPostBaseData 需要生成的默认字段]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $options [description]
	 * @return    [type]              [description]
	 */
	public static function getPostBaseData($data = [],$options = [])
	{
		$arr = [];
		$callback = function($val,$index) use(&$arr){
			switch ($val) {
				case 'device_info':
					$arr['device_info'] 		= 'WEB';
					break;
				case 'time_start':
					$arr['time_start'] 			= date('YmdHis',time());
					break;
				case 'time_expire':
					$arr['time_expire'] 		= date('YmdHis',time() + 2 * 60 * 60);
					break;
				case 'nonce_str':
					$arr['nonce_str'] 			= self::getRandStr();
					break;
				case 'bill_date':
					$arr['bill_date']			= (string)date('Ymd',time());
					break;
				case 'bill_type':
					$arr['bill_type']			= 'ALL';
					break;
				case 'trade_type':
					$arr['trade_type']			= 'JSAPI';
					break;
				case 'out_refund_no':
					$arr['out_refund_no']		= date('YmdHis').mt_rand(10000,99999);
					break;
				case 'wishing':
					$arr['wishing']				= '感谢您参加思智捷信息科技红包活动，祝您生活愉快！';
					break;
				case 'act_name':
					$arr['act_name']			= '思智捷科技红包活动';
					break;
				case 'remark':
					$arr['remark']			    = '由思智捷信息科技有限公司提供技术支持';
					break;
				case 'scene_id':
					$arr['scene_id']			= 'PRODUCT_1';
					break;
				case 'check_name':
					$arr['check_name']			= 'NO_CHECK';
					break;
				default:
					break;
			}
		};
		array_walk($options,$callback);
		$xml = self::arrayToXml(array_merge($arr,$data));
		return $xml;
	}



	/**
	 * CURL请求
	 * @param $url 请求url地址
	 * @param $method 请求方法 get post
	 * @param null $postfields post数据数组
	 * @param false $useCert    API证书
	 * @param array $headers 请求header信息
	 * @param bool|false $debug  调试开启 默认false
	 * @return mixed
	 */
	public static function curlRequset($url, $method, $postfields = null, $headers = array())
	{
	    $method = strtoupper($method);
	    $ci = curl_init();
	    /* Curl settings */
	    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
	    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
	    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
	    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
	    switch ($method) {
	        case "POST":
	            curl_setopt($ci, CURLOPT_POST, true);
	            if (!empty($postfields)) {
	                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
	                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
	            }
	            break;
	        default:
	            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
	            break;
	    }
	    $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
	    curl_setopt($ci, CURLOPT_URL, $url);
	    if($ssl){
	        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
	        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
	    }
	    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
	    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
	    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
	    /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
	    $response = curl_exec($ci);
	    $requestinfo=curl_getinfo($ci);
	    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
	    curl_close($ci);
	    return $response;
	}

	/**
	 * 带证书的发送
	 * @param  [type]  $url     [description]
	 * @param  [type]  $vars    [description]
	 * @param  integer $second  [description]
	 * @param  array   $aHeader [description]
	 * @return [type]           [description]
	 */
	public static function curlPostSsl($url, $vars, $cert = '',$key = '',$second = 60,$aHeader=array())
	{
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,$cert);
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,$key);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		}else { 
			$error = curl_errno($ch);
			curl_close($ch);
			return false;
		}
	}



	/**
	 * [getRandStr 获取随机字符串]
	 * @author    como
	 * @datetime  2019-04-03
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $len [指定长度]
	 * @return    [type]          [description]
	 */
	public static function getRandStr($length = 32)
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str   = "";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}

	/**
	 * [xmlToArray xml转成数组]
	 * @author    como
	 * @datetime  2019-04-03
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     [type]     $xml [description]
	 * @return    [type]          [description]
	 */
	public static function xmlToArray($xml = null)
	{
		if(empty($xml)) throw new \Exception('xml数据异常！', 1000002);
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(@simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
	}
	/**
	 * [arrayToXml 数据转xml]
	 * @author    como
	 * @datetime  2019-04-03
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @return    [type]     [description]
	 */
	public static function arrayToXml($data = [])
	{
		if(empty($data) || !is_array($data)) throw new \Exception('参数为空或参数不是数组', 1000001);
		$hanadelData = [];
		$callback = function($val,$key) use(&$hanadelData){
			if(is_array($val)) {
				$hanadelData[trim($key)] = json_encode($val,JSON_UNESCAPED_UNICODE);
			} else {
				$hanadelData[trim($key)] = $val;
			}
		};
		array_walk($data, $callback);
		ksort($hanadelData);
		if(!isset($hanadelData['sign'])) {
			$hanadelData['sign'] 		= self::makeSign($hanadelData);
		}
		if(isset($hanadelData['key'])) unset($hanadelData['key']);
		//print_r($hanadelData);die;
		return self::_arrayToXml($hanadelData);
	}
	/**
	 * [arrayToXmlRaw 原生数组转xml]
	 * @author 	   szjcomo
	 * @createTime 2020-01-10
	 * @param      array      $data [description]
	 * @return     [type]           [description]
	 */
	public static function arrayToXmlRaw(array $data)
	{
		return self::_arrayToXml($data);
	}
	/**
	 * [_arrayToXml 数组转xml]
	 * @author    como
	 * @datetime  2019-03-22
	 * @version   [1.5.0]
	 * @param     array      $arr [description]
	 * @return    [type]          [description]
	 */
	protected static function _arrayToXml($data = [],$root='xml', $attr ='')
	{
	    if(is_array($attr)){
	        $_attr = array();
	        foreach ($attr as $key => $value) {
	            $_attr[] = "{$key}=\"{$value}\"";
	        }
	        $attr = implode(' ', $_attr);
	    }
	    $attr   = trim($attr);
	    $attr   = empty($attr) ? '' : " {$attr}";
	    $xml 	= '';
	    $xml   .= "<{$root}{$attr}>";
	    $xml   .= self::data_to_xml($data);
	    $xml   .= "</{$root}>";
	    return $xml;
	}
	/**
	 * [数据XML编码]
	 * @作者     como
	 * @时间     2018-07-16
	 * @版本     1.0.1
	 * @param  [type]     $data [description]
	 * @return [type]           [description]
	 */
	public static function data_to_xml($data) 
	{
	    $xml = '';
	    foreach ($data as $key => $val) {
	        is_numeric($key) && $key = "item";
	        $xml    .=  "<$key>";
	        $xml    .=  ( is_array($val) || is_object($val)) ? self::data_to_xml($val)  : self::xmlSafeStr($val);
	        list($key, ) = explode(' ', $key);
	        $xml    .=  "</$key>";
	    }
	    return $xml;
	}
	/**
	 * [xmlSafeStr xml转换成字符串]
	 * @作者     como
	 * @时间     2018-07-16
	 * @版本     1.0.1
	 * @param  [type]     $str [description]
	 * @return [type]          [description]
	 */
	public static function xmlSafeStr($str)
	{   
		return '<![CDATA['.preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$str).']]>';   
	} 

	/**
	 * [MakeSign 生成支付签名]
	 * @author    como
	 * @datetime  2019-04-03
	 * @version   [1.5.0]
	 * @param     array      $data [description]
	 */
	protected static function makeSign($data = [])
	{
		ksort($data);
		$string = '';
		foreach($data as $k=>$v){
			if($k != 'key') $string .= $k.'='.$data[$k].'&';		
		}
		if(isset($data['key']))
			$string = $string.'key='.$data['key'];
		return strtoupper(md5($string));
	}

}

