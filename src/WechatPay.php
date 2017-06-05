<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/6/1
 * Time: 22:37
 */

namespace Notadd\Multipay;
use Omnipay\Omnipay;
use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Illuminate\Http\Request;
use Omnipay\WechatPay\Helper;
use Omnipay\WechatPay\Message\CreateOrderRequest;
use Omnipay\WechatPay\Message\RefundOrderRequest;

class WechatPay
{
    protected $settings;
    protected $app;
    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);

    }
    public function register(){
        $this->app->singleton('pay_native',function($app){
            return new Wechatpay($app->pay_native);
        });
        $this->app->singleton('pay_jsapi',function($app){
            return new Wechatpay($app->pay_jsapi);
        });
        $this->app->singleton('pay_pos',function($app){
            return new Wechatpay($app->pay_pos);
        });
    }

     /*
     * 上传证书
     */

    public function uploadcert(Request $request){
        $path ='./cert/'.date('Ymd');
        $filename = $_FILES['cert']['name'];
        $request->file('cert')->move($path,$filename);
    }
    public function getData()
    {
        $this->validate(
            'app_id',
            'mch_id',
            'body',
            'out_trade_no',
            'total_fee',
            'notify_url',
            'trade_type',
            'spbill_create_ip'
        );

        $tradeType = strtoupper($this->getTradeType());

        if ($tradeType == 'JSAPI') {
            $this->validate('open_id');
        }
        $order = new CreateOrderRequest();
        //创建订单配置
        $data = array(
            'appid'            => $order->getAppId(),
            'mch_id'           => $order->getMchId(),
            'device_info'      => $order->getDeviceInfo(),
            'body'             => $order->getBody(),
            'detail'           => $order->getDetail(),
            'attach'           => $order->getAttach(),
            'out_trade_no'     => $order->getOutTradeNo(),
            'fee_type'         => $order->getFeeType(),
            'total_fee'        => $order->getTotalFee(),
            'spbill_create_ip' => $order->getSpbillCreateIp(),
            'time_start'       => $order->getTimeStart(),
            'time_expire'      => $order->getTimeExpire(),
            'goods_tag'        => $order->getGoodsTag(),
            'notify_url'       => $order->getNotifyUrl(),
            'trade_type'       => $order->getTradeType(),
            'limit_pay'        => $order->getLimitPay(),
            'openid'           => $order->getOpenId(),
            'nonce_str'        => md5(uniqid()),
        );
        $data = array_filter($data);

        $data['sign'] = Helper::sign($data, $order->getApiKey());

        return $data;
    }

    //退款订单配置
    public function getrefundData()
    {
        $this->validate('app_id', 'mch_id', 'out_trade_no', 'cert_path', 'key_path');

        $refund = new RefundOrderRequest();

        $data = array (
            'appid'           => $refund->getAppId(),
            'mch_id'          => $refund->getMchId(),
            'device_info'     => $refund->getDeviceInfo(),
            'transaction_id'  => $refund->getTransactionId(),
            'out_trade_no'    => $refund->getOutTradeNo(),
            'out_refund_no'   => $refund->getOutRefundNo(),
            'total_fee'       => $refund->getTotalFee(),
            'refund_fee'      => $refund->getRefundFee(),
            'refund_fee_type' => $refund->getRefundType(),
            'op_user_id'      => $refund->getOpUserId() ?: $refund->getMchId(),
            'nonce_str'       => md5(uniqid()),
        );

        $data = array_filter($data);

        $data['sign'] = Helper::sign($data, $refund->getApiKey());

        return $data;
    }


    /*
     * 扫码支付
     */
    public function get_wechat_Nativegateway(){
        $gateway = Omnipay::create('WechatPay_Native');
        $gateway->setAppId($this->settings->get('wechat.app_id'));
        $gateway->setMchId($this->settings->get('wechat.mch_id'));
        $gateway->setApiKey($this->settings->get('wechat.key'));

        return $gateway;
    }
    public function pay_native(){
        $params = [
            'sign' => $this->getData($data['sign']),
            'body' => $this->getData($data['body']),
            'nonce_type'=> $this->getData($data['nonce_type']),
            'out_trade_no' => $this->getData($data['out_trade_no']),
            'total_fee' => $this->getData($data['total_fee']),
            'spbill_create_ip' => $this->getData($data['spbill_create_ip']),
            'notify_url' =>$this->getData($data['notify_url']),
            'trade_type'=>$this->getData($data['trade_type'])
        ];

        // 获取支付网关
        $gateway = $this->get_wechat_Nativegateway();

        $response = $gateway->purchase($params)->send();

        $response->isSuccessful();
    }
    //回调通知
    public function nativenotify(){
        $gateway = $this->get_wechat_Nativegateway();
        $request = $gateway->completePurchase();
        if ( $request->isPaid()) {

            echo "支付成功";
        } else {
            echo "支付失败";
        }
    }
    //退款
    public function nativerefund(){
        $gateway = $this->get_wechat_Nativegateway();
        $gateway->setCertPath($this->settings->get('wechat.certpath'));
        $gateway->setKeyPath($this->settings->get('wechat.keypath'));
        $response = $gateway->refund([
            'nonce_str'=>$this->getrefundData($data['nonce_str']),
            'transaction_id'=>$this->getrefundData($data['transaction_id']),
            'sign'=>$this->getrefundData($data['sign']),
            'out_refund_no'=>$this->getrefundData($data['out_refund_no']),
            'total_fee'=>$this->getrefundData($data['total_fee']),
            'refund_fee'=>$this->getrefundData($data['refund_fee'])
        ])->send();
        $response->isSuccessful();
    }

    /*
     * 公众号支付
     */
    public function get_wechat_Jsgateway(){

        $gateway = Omnipay::create('WechatPay_Js');
        $gateway->setAppId($this->settings->get('wechat.app_id'));
        $gateway->setMchId($this->settings->get('wechat.mch_id'));
        $gateway->setApiKey($this->settings->get('wechat.key'));
    }
    public function pay_jsapi(){
        $params = [
            'sign' => $this->getData($data['sign']),
            'body' => $this->getData($data['body']),
            'openid'=>$this->getData($data['openid']),
            'nonce_type'=> $this->getData($data['nonce_type']),
            'out_trade_no' => $this->getData($data['out_trade_no']),
            'total_fee' => $this->getData($data['total_fee']),
            'spbill_create_ip' => $this->getData($data['spbill_create_ip']),
            'notify_url' =>$this->getData($data['notify_url']),
            'trade_type'=>$this->getData($data['trade_type'])
        ];

        // 获取支付网关
        $gateway = $this->get_wechat_Jsgateway();

        $response = $gateway->purchase($params)->send();

        $response->isSuccessful();

    }
    //回调通知
    public function jsnotify()
    {
        $gateway = $this->get_wechat_Jsgateway();
        $request = $gateway->completePurchase();
        if ($request->isPaid()) {

            echo "支付成功";
        } else {
            echo "支付失败";
        }
    }
    //退款
    public function jsrefund(){
        $gateway = $this->get_wechat_Jsgateway();
        $gateway->setCertPath($this->settings->get('wechat.certpath'));
        $gateway->setKeyPath($this->settings->get('wechat.keypath'));
        $response = $gateway->refund([
            'nonce_str'=>$this->getrefundData($data['nonce_str']),
            'transaction_id'=>$this->getrefundData($data['transaction_id']),
            'sign'=>$this->getrefundData($data['sign']),
            'out_refund_no'=>$this->getrefundData($data['out_refund_no']),
            'total_fee'=>$this->getrefundData($data['total_fee']),
            'refund_fee'=>$this->getrefundData($data['refund_fee'])
        ])->send();
        $response->isSuccessful();
    }

     /*
      * 刷卡支付
      */
    public function get_wechat_Posgateway(){

        $gateway = Omnipay::create('WechatPay_Pos');
        $gateway->setAppId($this->settings->get('wechat.app_id'));
        $gateway->setMchId($this->settings->get('wechat.mch_id'));
        $gateway->setApiKey($this->settings->get('wechat.key'));
    }
    public function pay_pos(){
        $params = [
            'sign' => $this->getData($data['sign']),
            'body' => $this->getData($data['body']),
            'nonce_type'=> $this->getData($data['nonce_type']),
            'out_trade_no' => $this->getData($data['out_trade_no']),
            'total_fee' => $this->getData($data['total_fee']),
            'spbill_create_ip' => $this->getData($data['spbill_create_ip']),
            'notify_url' =>$this->getData($data['notify_url']),
            'trade_type'=>$this->getData($data['trade_type'])
        ];

        // 获取支付网关
        $gateway = $this->get_wechat_Posgateway();

        $response = $gateway->purchase($params)->send();

        $response->isSuccessful();

    }
    //回调通知
    public function posnotify(){
        $gateway = $this->get_wechat_Posgateway();
        $request = $gateway->completePurchase();
        if ( $request->isPaid()) {

            echo "支付成功";
        } else {
            echo "支付失败";
        }
    }
    //退款
    public function posrefund(){
        $gateway = $this->get_wechat_Posgateway();
        $gateway->setCertPath($this->settings->get('wechat.certpath'));
        $gateway->setKeyPath($this->settings->get('wechat.keypath'));
        $response = $gateway->refund([
            'nonce_str'=>$this->getrefundData($data['nonce_str']),
            'transaction_id'=>$this->getrefundData($data['transaction_id']),
            'sign'=>$this->getrefundData($data['sign']),
            'out_refund_no'=>$this->getrefundData($data['out_refund_no']),
            'total_fee'=>$this->getrefundData($data['total_fee']),
            'refund_fee'=>$this->getrefundData($data['refund_fee'])
        ])->send();
        $response->isSuccessful();
    }
}