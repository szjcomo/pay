<?php



require './vendor/autoload.php';

ini_set('date.timezone','Asia/Shanghai');

use szjcomo\pay\WechatPay;

$appid = 'xxx';
$mch_id = 'xxx';
$key = 'xxx';
$out_trade_no = date('YmdHis').mt_rand(10000,99999).'';

/**
 * [$res 统一下单接口]
 * @var [type]
 */
/*$config = [
    'appid'=>$appid,
    'mch_id'=>$mch_id,
    'body'=>'思智捷科技测试支付',
    'out_trade_no'=>$out_trade_no,
    'total_fee'=>1,
    'spbill_create_ip'=>'192.168.1.165',
    'notify_url'=>'http://xxx',
    'trade_type'=>'NATIVE',
    'key'=>$key
];*/
//$res = WechatPay::unifiedorder($config);
/**
 * [$res 订单关闭接口]
 * @var [type]
 */
/**
$config = [
    'appid'=>$appid,
    'mch_id'=>$mch_id,
    'out_trade_no'=>'xxx',
    'key'=>$key
];**/
//$res = WechatPay::closeOrder($config);
/**
 * [$res 查询订单接口]
 * @var [type]
 */
/**
$config = [
    'appid'=>$appid,
    'mch_id'=>$mch_id,
    'out_trade_no'=>'xxx',
    'key'=>$key
];*/
//$res = WechatPay::queryOrder($config);

/*$config = [
	'appid'=>$appid,
	'mch_id'=>$mch_id,
	'out_trade_no'=>'2020010213580595612',
	'out_refund_no'=>$out_trade_no,
	'total_fee'=>1,
	'refund_fee'=>1,
	'key'=>$key
];*/
/**
 * [$res 发起订单退款]
 * @var [type]
 */
/*$res = WechatPay::refundOrder($config,'./apiclient_cert.pem','./apiclient_key.pem');*/


/*$config = [
	'appid'=>$appid,
	'mch_id'=>$mch_id,
	'out_trade_no'=>'2020010213580595612',
	'key'=>$key
];*/
/**
 * [$res 查询退款信息]
 * @var [type]
 */
//$res = WechatPay::refundQuery($config);
/*print_r($res);*/