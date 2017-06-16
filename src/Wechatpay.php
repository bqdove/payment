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
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
class Wechatpay
{
    protected $settings;
    protected $gateway;

    public function __construct()
    {
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }


    /*
     * 获取支付网关
     */
    public function getGateWay($gatewayname)
    {

        $this->gateway = Omnipay::create($gatewayname);
        $this->gateway->setAppId('wx081bfce94ce71bfb');
        $this->gateway->setMchId('1268498801');
        $this->gateway->setApiKey('t4IYxcncB94TMAp5c0ZCkQKwjseDJBGA');
//        $this->gateway->setApiKey('a9afd80709f76892dd541f9c6aa6365a');//沙箱api秘钥
        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080');

        return $this;
    }

    public function pay(Array $para)
    {

        //http://pay.ibenchu.xyz:8080/api/multipay/pay?driver=wechat&way=WechatPay_Native&app_id=wx2dd40b5b1c24a960&mch_id=1235851702&body=Iphone8&total_fee=10&out_trade_no=201706091212121000&spbill_create_ip=36.45.175.53&notify_url=http://pay.ibenchu.xyz:8080&nonce_str=c3b570e1c8441c0ae1f435c3c4de8464&trade_type=NATIVE


        $para = [
            'body' => 'test',
            'notify_url' => 'http://lxnotadd.com',
            'out_trade_no' => '201706091212121007',
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 3,
            'trade_type' => 'NATIVE',
        ];


        $response = $this->gateway->purchase($para)->send();

        $code_url = $response->getCodeUrl();

        $qrCode = new QrCode();
        $qrCode->setText( $code_url )
            ->setWriterByName('png')
            ->setMargin(10)
            ->setEncoding('UTF-8')
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::LOW)
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
            ->setValidateResult(false);
        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();

        $qrCode->writeFile(__DIR__.'/qrcode.png');


    }


    //回调通知
    public function webNotify(Array $para){


        $sign = Helper::getsign($para);

        $para2 = [
            'sign' => $sign
        ];

        $options = $para + $para2;
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

        $para = [
            'body' => 'test',
            'notify_url' => 'http://lxnotadd.com',
            'out_trade_no' => '201706091212121004',
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 1,
            'trade_type' => 'NATIVE',
        ];

        $response = $this->gateway->query($para)->send();
        dd($response);
        $response->isSuccessful();
    }

    //退款
    public function refund(Array $para){
        $certpath = '../strorage/uploads';
        $keypath = '../strorage/uploads';

        $para = [
            'body' => 'test',
            'notify_url' => 'http://lxnotadd.com',
            'out_trade_no' => '201706091212121004',
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 1,
            'trade_type' => 'NATIVE',
            'certpath'=>$certpath,
            'keypath'=>$keypath
        ];
        $response = $this->gateway->refund($para)->send();
        dd($response);
        $response->isSuccessful();
    }

    //取消

    public function cancel(Array $para)
    {

        $para = [
            'body' => 'test',
            'notify_url' => 'http://lxnotadd.com',
            'out_trade_no' => '201706091212121005',
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 1,
            'trade_type' => 'NATIVE',
        ];


        $response = $this->gateway->close($para)->send();
        dd($response);
//        var_dump($response->isSuccessful());
//        var_dump($response->getData());
    }

}

