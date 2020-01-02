# 思智捷科技支付类



## 微信支付 (szjcomo\pay\WechatPay)

- 提供方法

|  方法名称 | 方法类型  | 是否静态  | 其它说明  |
| ------------ | ------------ | ------------ | ------------ |
|  unifiedOrder |  public | static  | 统一下单  |
|  closeOrder |  public | static  | 关闭订单  |
|  queryOrder |  public | static  | 订单查询  |
|  refundOrder |  public | static  | 订单退款  |
| refundQuery  | public  | static  | 退款查询  |


- unifiedOrder (统一下单)

| 参数名称  |  是否必须 | 参数说明  |  其它说明 |
| ------------ | ------------ | ------------ | ------------ |
|  appid | Y  |  公众号appid |   |
|  mch_id | Y  | 商户号  |   |
|  body | Y  | 商品说明  |   |
|  out_trade_no | Y  | 订单号  |  商家自己生成的订单号 |
|  total_fee | Y  | 支付金额  |  以分为单位 |
|  spbill_create_ip | Y  | 客户端IP  |   |
|  notify_url |  Y |  回调地址 | 可访问的地址  |
|  trade_type | Y  | 下单类型  | NATIVE(二维码) JSAPI(h5或公众号)支付 APP(APP支付) |
|  key | Y  | 支付密钥  |   |
|  更多参数 | 参考微信  | 支付  | 说明  |


- closeOrder (关闭订单)

| 参数名称  |  是否必须 | 参数说明  |  其它说明 |
| ------------ | ------------ | ------------ | ------------ |
|  appid | Y  |  公众号appid |   |
|  mch_id | Y  | 商户号  |   |
|  out_trade_no | Y  | 订单号  |  商家自己生成的订单号 |
|  key | Y  | 支付密钥  | 支付密钥   |

- queryOrder (订单查询)

| 参数名称  |  是否必须 | 参数说明  |  其它说明 |
| ------------ | ------------ | ------------ | ------------ |
|  appid | Y  |  公众号appid |   |
|  mch_id | Y  | 商户号  |   |
|  out_trade_no | Y  | 订单号  |  商家自己生成的订单号 |
|  key | Y  | 支付密钥  | 支付密钥   |

- refundOrder (订单退款)

| 参数名称  |  是否必须 | 参数说明  |  其它说明 |
| ------------ | ------------ | ------------ | ------------ |
|  appid | Y  |  公众号appid |   |
|  mch_id | Y  | 商户号  |   |
|  out_trade_no | Y  | 订单号  |  商家自己生成的订单号 |
|  out_refund_no | Y  | 退款单号  |  商家自己生成的退款订单号 |
|  total_fee | Y  | 订单金额  |  以分为单位 |
|  refund_fee | Y  | 退款金额  |  以分为单位 |
|  key | Y  | 支付密钥  | 支付密钥   |
|  cert_pem | Y  | 退款证书  | 当作第二个参数传入   |
|  key_pem | Y  | 退款证书  | 当作第三个参数传入   |



- refundQuery (退款查询)

| 参数名称  |  是否必须 | 参数说明  |  其它说明 |
| ------------ | ------------ | ------------ | ------------ |
|  appid | Y  |  公众号appid |   |
|  mch_id | Y  | 商户号  |   |
|  out_trade_no | Y  | 订单号  |  商家自己生成的订单号 |
|  key | Y  | 支付密钥  | 支付密钥   |