<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/23
 * Time: 18:22
 */

namespace Notadd\Multipay;
use Latrell\Wxpay\Pay\Micro;
use Latrell\Wxpay\Pay\Refund;
use Latrell\Wxpay\pay\JsApi;
use Latrell\Wxpay\pay\Native;
use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Latrell\Wxpay\Models\UnifiedOrder;
use Latrell\Wxpay\Sdk\Api;
use Illuminate\Http\Request;
use Latrell\Wxpay\Models\Refund as RefundModel;
use Latrell\Wxpay\Models\MicroPay;
class Wechatpay
{
    protected $settings;
    protected $app;
    protected $input;
    protected $config=[];
    CONST SSLCERT_PATH = 'wxpay/config/cert/apiclient_cert.pem';
    CONST SSLKEY_PATH = 'wxpay/config/cert/apiclient_key.pem';
    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    public function getconfig($config){
        return $this->settings->get($config);
    }

    /*
     * 统一下单
     */
    public function unifiedOrder(){
        $api=new Api($options);
        $params['body']=$this->settings->get('wechat.body');
        $params['out_trade_no']=$this->settings->get('wechat.out_trade_no');
        $params['total_fee'] = $this->settings->get('wechat.total_fee');
        $params['trade_type']=$this->settings->get('wechat.trade_type');
        $api->unifideOrder($params);
    }

    public function register(){
        $this->app->singleton('jsapi',function($app){
            return new Wechatpay($app->jsapi);
        });
        $this->app->singleton('micro',function($app){
            return new Wechatpay($app->micro);
        });
        $this->app->singleton('native',function($app){
            return new Wechatpay($app->native);
        });
    }

    /*
     * 公众号支付
     */
    public function jsapi(){

        $jsApi = new JsApi($this->config);
        //网页授权获取用户openid
       $openid = $jsApi->GetOpenidFromMp($_GET['code']);
       $input = new UnifiedOrder();
       $api = new Api();
       $order = $api->UnifiedOrder($input);
       $parameters = $jsApi->GetJsApiParameters($order);

    }

    /*
     * 扫码支付
     */
    public function native(){
        $native = new Native($this->config);
        //模式一
        $productId = $this->settings->get('wechat.productId');
        $url = $native->GetPrePayUrl($productId);
        return $url;
        //模式二
        $input = new UnifiedOrder();
        $result = $native->GetpayUrl($input);
        return $result;
    }

    /*
    * 刷卡支付
    */
    public function micro(){
        $input = new MicroPay();
        return new Micro($input);
    }

    /*
     * 回调通知
     */
    public function notify(){
       $api = new Api();
       $verify = $api->notify();
       if($verify!==false){
           echo 'success';
       }else{
           echo 'fail';
       }
   }

    /*
     * 申请退款
     */
    public function refund(){
        $input = new RefundModel();
        return new Refund($input);
    }
    /*
     * 上传证书
     */
    public function uploadcert(Request $request){
        $path ='./cert/'.date('Ymd');
        $filename = rand(1000,90000).'.'.'pem';
        $request->file('cert')->move($path,$filename);
    }
}