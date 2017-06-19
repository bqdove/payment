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
        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080/webnotify');

        return $this;
    }

    public function pay(Array $para)
    {

       // http://lxnotadd.com/webnotify?driver=wechat&way=WechatPay_Native&app_id=wx081bfce94ce71bfb&mch_id=1268498801&body=test&total_fee=1&out_trade_no=201706091212121004&spbill_create_ip=36.45.175.53&notify_url=http://lxnotadd.com&trade_type=NATIVE


        $para = [
            'body' => 'test',
//            'openid'=>'oTIyBw_wQrCOWeQg4ybxsAyiv70E',
//            'notify_url' => 'http://pay.ibenchu.xyz:8080/api/multipay/webnotify',
            'out_trade_no' => '201706091212121029',
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

        if ($response->isSuccessful())
        {
            return 1;
        }

    }

    //回调通知
    public function webNotify(){

        $response = $this->gateway->completePurchase(['request_params' => file_get_contents('php://input')])->send();

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
            'out_trade_no' => '201706091212121007',
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 3,
            'trade_type' => 'NATIVE',
        ];

        $response = $this->gateway->query($para)->send();
        dd($response);
        $response->isSuccessful();
    }

    //退款
    public function refund(Array $para){
        $para = [
            'body' => 'test',
            'notify_url' => 'http://lxnotadd.com',
            'out_trade_no' => '201706091212121004',
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 1,
            'trade_type' => 'NATIVE',
            'out_refund_no'=>'126849880120170616161813' ,
            'refund_fee'=>1,
            'cert_path'=>public_path('weixin/cert/apiclient_cert.pem'),
            'key_path'=>public_path('weixin/cert/apiclient_key.pem')
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
            'out_trade_no' => '201706091212121006',
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

