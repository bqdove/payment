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
use Notadd\Multipay\Helper as Helper;

class Wechatpay
{
    protected $settings;
    protected $gateway;

    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }


    /*
     * 获取支付网关
     */
    public function getGateWay($gatewayname){

        $this->gateway = Omnipay::create($gatewayname);

        return $this;
    }

    public function pay(Array $para)
    {

        //http://pay.ibenchu.xyz:8080/api/multipay/pay?driver=wechat&way=WechatPay_Native&app_id=wx2dd40b5b1c24a960&mch_id=1235851702&body=Iphone8&total_fee=10&out_trade_no=201706091212121000&spbill_create_ip=36.45.175.53&notify_url=http://pay.ibenchu.xyz:8080&nonce_str=c3b570e1c8441c0ae1f435c3c4de8464&trade_type=NATIVE


        $sign = Helper::getsign($para);

        $para2 = [
            'sign' => $sign
        ];

        $originPara = $para+$para2;

        $response = $this->gateway->purchase($originPara)->send();

        dd($response);
        //available methods
 //       $response->getData(); //For debug
//        $response->getAppOrderData(); //For WechatPay_App
//        $response->getJsOrderData(); //For WechatPay_Js
        dd($response->getCodeUrl()); //For Native Trade Type

    }

    //回调通知
    public function webNotify(Array $para){


        $sign = Helper::getsign($para);

        $para2 = [
            'sign' => $sign
        ];

        $options = $originPara + $para2;
        if('return_code'=='SUCCESS' && $sign === $options['sign']){

        }
        $response = $this->gateway->completePurchase($options)->send();
        if ( $response->isPaid()) {
            var_dump($response->getData());
        } else {
            echo "支付失败";
        }
    }
    //查询
    public function query(Array $para){

        $sign = Helper::getsign($para);

        $para2 = [
            'sign' => $sign
        ];

        $originPara = $para + $para2;
        $response = $this->gateway->query($originPara)->send();

        $response->isSuccessful();
    }

    //退款
    public function refund(Array $para){
        $certpath = '../strorage/uploads';
        $keypath = '../strorage/uploads';

        $para1=[
            'certpath'=>$certpath,
            'keypath'=>$keypath,
        ];
        $para = $para+$para1;
        $sign = Helper::getsign($para);
        $para2 = [

            'sign' => $sign
        ];
        $originPara = $para + $para2;
        $response = $this->gateway->refund($originPara)->send();
        $response->isSuccessful();
    }

    //取消

    public function cancel(Array $para)
    {

        $sign = Helper::getsign($para);

        $para2 = [
            'sign' => $sign
        ];

        $originPara = $para + $para2;
        $response = $this->gateway->close($originPara)->send();
        var_dump($response->isSuccessful());
        var_dump($response->getData());
    }

}