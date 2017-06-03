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
use Omnipay\WechatPay\BaseAbstractGateway;
class Wechatpay
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
        $nonce_str = $this->settings->get('wechat.nonce_str');
        $sign = $this->settings->get('wechat.sign');
        $body = $this->settings->get('wechat.body');
        $out_trade_no = $this->settings->get('wechat.out_trade_no');
        $total_fee = $this->settings->get('total_fee');
        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];
        $notify_url = 'https://api.mch.weixin.qq.com/pay/native';

        $params = [
          'body'=>$body,
          'nonce_str'=>$nonce_str,
          'sign'=>$sign,
          'out_trade_no'=>$out_trade_no,
          'total_fee'=>$total_fee,
          'spbill_create_ip'=>$spbill_create_ip,
          'notify_url'=>$notify_url
        ];

        // 获取支付网关
        $gateway = $this->get_wechat_Nativegateway();

        $response = $gateway->shortenUrl($params)->send();

        $response->redirect();
    }
    //回调通知
    public function nativenotify(){
        $gateway = $this->get_wechat_Nativegateway();
        $request = $gateway->completePurchase();
        if ( $request->isPaid()) {
            $total_fee = $_GET[ 'total_fee' ];
            $trade_no = $_GET[ 'trade_no' ];
            $out_trade_no = $_GET[ 'out_trade_no' ];
            $body = $_GET[ 'body' ];

            echo "支付成功";
        } else {
            echo "支付失败";
        }
    }
    //退款
    public function nativerefund(){
        $gateway = $this->get_wechat_Nativegateway();
        $gateway->setCertPath($this->settings->get('path'));
        $gateway->setKeyPath($this->settings->get('path'));
        $response = $gateway->refund([
            'nonce_str'=>$this->settings->get('wechat.nonce_str'),
            'out_trade_no'=>$this->settings->get('wechat.out_trade_no'),
            'sign'=>$this->settings->get('wechat.sign'),
            'out_refund_no'=>$this->settings->get('wechat.out_refund_no'),
            'total_fee'=>$this->settings->get('wechat.total_fee'),
            'refund_fee'=>$this->settings->get('wechat.redund_fee')
        ])->send();
    }

    /*
     * 公众号支付
     */
    public function get_wechat_Jsgateway(){

        $gateway = Omnipay::create('WechatPay_Js');
        $gateway->setAppId($config['app_id']);
        $gateway->setMchId($config['mch_id']);
        $gateway->setApiKey($config['wkey']);
    }
    public function pay_jsapi(){
        $openid = $this->settings->get('wechat.openid');//jsapi必传
        $nonce_str = $this->settings->get('wechat.nonce_str');
        $sign = $this->settings->get('wechat.sign');
        $appsecret = $this->settings->get('wechat.appsecret');//jsapi必传
        $body = $this->settings->get('wechat.body');
        $out_trade_no = $this->settings->get('wechat.out_trade_no');
        $total_fee = $this->settings->get('total_fee');
        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];
        $notify_url = 'https://api.mch.weixin.qq.com/pay/jsapi';

        $params = [
            'body'=>$body,
            'open_id'=>$openid,
            'appsecret'=>$appsecret,
            'nonce_str'=>$nonce_str,
            'sign'=>$sign,
            'out_trade_no'=>$out_trade_no,
            'total_fee'=>$total_fee,
            'spbill_create_ip'=>$spbill_create_ip,
            'notify_url'=>$notify_url
        ];
        // 获取支付网关
        $gateway = $this->get_wechat_Jsgateway();

        $response = $gateway->purchase($params)->send();

        $response->redirect();

    }
    //回调通知
    public function jsnotify()
    {
        $gateway = $this->get_wechat_Jsgateway();
        $request = $gateway->completePurchase();
        if ($request->isPaid()) {
            $total_fee = $_GET['total_fee'];
            $trade_no = $_GET['trade_no'];
            $out_trade_no = $_GET['out_trade_no'];
            $body = $_GET['body'];

            echo "支付成功";
        } else {
            echo "支付失败";
        }
    }
    //退款
    public function jsrefund(){
        $gateway = $this->get_wechat_Jsgateway();
        $gateway->setCertPath($this->settings->get('path'));
        $gateway->setKeyPath($this->settings->get('path'));
        $response = $gateway->refund([
            'nonce_str'=>$this->settings->get('wechat.nonce_str'),
            'out_trade_no'=>$this->settings->get('wechat.out_trade_no'),
            'sign'=>$this->settings->get('wechat.sign'),
            'out_refund_no'=>$this->settings->get('wechat.out_refund_no'),
            'total_fee'=>$this->settings->get('wechat.total_fee'),
            'refund_fee'=>$this->settings->get('wechat.redund_fee')
        ])->send();
    }

     /*
      * 刷卡支付
      */
    public function get_wechat_Posgateway(){

        $gateway = Omnipay::create('WechatPay_Pos');
        $gateway->setAppId($config['app_id']);
        $gateway->setMchId($config['mch_id']);
        $gateway->setApiKey($config['wkey']);
    }
    public function pay_pos(){
        $enabled = $this->settings->get('wechat.wechat_enabled'); //是否开启微信支付
        $nonce_str = $this->settings->get('wechat.nonce_str');
        $sign = $this->settings->get('wechat.sign');
        $body = $this->settings->get('wechat.body');
        $out_trade_no = $this->settings->get('wechat.out_trade_no');
        $total_fee = $this->settings->get('total_fee');
        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];
        $notify_url = 'https://api.mch.weixin.qq.com/pay/pos';

        $params = [
            'body'=>$body,
            'nonce_str'=>$nonce_str,
            'sign'=>$sign,
            'out_trade_no'=>$out_trade_no,
            'total_fee'=>$total_fee,
            'spbill_create_ip'=>$spbill_create_ip,
            'notify_url'=>$notify_url
        ];

        // 获取支付网关
        $gateway = $this->get_wechat_Posgateway();

        $response = $gateway->purchase($params)->send();

        $response->redirect();
    }
    //回调通知
    public function posnotify(){
        $gateway = $this->get_wechat_Posgateway();
        $request = $gateway->completePurchase();
        if ( $request->isPaid()) {
            $total_fee = $_GET[ 'total_fee' ];
            $trade_no = $_GET[ 'trade_no' ];
            $out_trade_no = $_GET[ 'out_trade_no' ];
            $body = $_GET[ 'body' ];

            echo "支付成功";
        } else {
            echo "支付失败";
        }
    }
    //退款
    public function posrefund(){
        $gateway = $this->get_wechat_Nativegateway();
        $gateway->setCertPath($this->settings->get('path'));
        $gateway->setKeyPath($this->settings->get('path'));
        $response = $gateway->refund([
            'nonce_str'=>$this->settings->get('wechat.nonce_str'),
            'out_trade_no'=>$this->settings->get('wechat.out_trade_no'),
            'sign'=>$this->settings->get('wechat.sign'),
            'out_refund_no'=>$this->settings->get('wechat.out_refund_no'),
            'total_fee'=>$this->settings->get('wechat.total_fee'),
            'refund_fee'=>$this->settings->get('wechat.redund_fee')
        ])->send();
    }
}