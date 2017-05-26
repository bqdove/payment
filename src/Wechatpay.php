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
use Latrell\Wxpay\WxpayException;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
class Wechatpay
{
    protected $config;
    protected $settings;

    public function __construct($config){
        $this->config = $config;
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    public function getconfig($name=null){
        if(is_null($name)){
            return $this->config;
        }
        return array_get($this->config,$name, null);
    }
    /*
     * 公众号支付
     */
    public function jsapi(){
        $jsApi = new JsApi();
        //网页授权获取用户openid
       $openid = $jsApi->Getopenid();
       $jsApi->GetJsApiParameters();
    }
    /*
     * 刷卡支付
     */
    public function micro(){
        return new Micro($this->config);
    }
    /*
     * 扫码支付
     */
    public function native(){
        $native = new Native();
        //模式一
        $productId = $this->settings->get('wechat.product_id');
        return $native->GetPrePayUrl($productId);
        //模式二
        $result =  $native->GetPayUrl($input);
        return $result;
    }

    /*
     * 申请退款
     */
    public function refund(){
        return new Refund($this->config);
    }
}