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
use Latrell\Wxpay\Sdk\Notify;
use Latrell\Wxpay\Models\BizPayUrl;
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
        $this->settings->get($config);
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

        $jsApi = new JsApi();
        //网页授权获取用户openid
       $openid = $jsApi->GetOpenidFromMp($_GET['code']);
       $input = new UnifiedOrder();
       $api = new Api();
       $order = $api->UnifiedOrder($input);
       $parameters = $jsApi->GetJsApiParameters($order);
       //支付结果通知
        $notify =  new Notify();
        $result = $notify->handle(false);
        return $result;
    }

    /*
     * 扫码支付
     */
    public function native(){
        $native = new Native();
        $biz = new BizPayUrl();
        //模式一
        $productId = $this->settings->get('wechat.$productId');
        $url = $native->GetPrePayUrl($productId);
        return $url;
        //模式二
        $result = $native->GetPayUrl();
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
     * 申请退款
     */
    public function refund(){
        $input = new RefundModel();
        return new Refund($input);
    }
}