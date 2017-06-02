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
use Latrell\Wxpay\Models\Base;
class Wechatpay
{
    protected $settings;
    protected $app;
    protected $input;
    protected $config=[];

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
        $api=new Api();
        $unifi = new UnifiedOrder();
        $base = new Base();
        $params['appid']=$this->settings->get('wechat.appid');
        $params['mch_id']=$this->settings->get('wechat.mch_id');
        $params['nonce_str'] =$unifi->getNonceStr();
        $params['sign']=$base->getSign();
        $params['body']=$unifi->getbody();
        $params['out_trade_no']=$unifi->getOutTradeNo();
        $params['total_fee']=$this->settings->get('total_fee');
        $params['spbill_create_ip']=$_SERVER['REMOTE_ADDR'];
        $params['notify_url']='http://www.weixin.qq.com/wxpay/pay.php';
        $params['trade_type']=$this->settings->get('trade_type');
        $api->unifiedOrder($params);
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
    public function jsapi($trade_type='JSAPI'){

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
    public function native($trade_type='NATIVE'){
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
        return new Micro($this->config);
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
        return new Refund($this->config);
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