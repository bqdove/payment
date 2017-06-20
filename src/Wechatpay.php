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
use Notadd\Multipay\Models\Order;
use Illuminate\Support\Facades\Log;

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
        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080/api/multipay/wechat/webnotify');

        return $this;
    }

    public function pay(Array $para)
    {

       // http://lxnotadd.com/webnotify?driver=wechat&way=WechatPay_Native&app_id=wx081bfce94ce71bfb&mch_id=1268498801&body=test&total_fee=1&out_trade_no=201706091212121004&spbill_create_ip=36.45.175.53&notify_url=http://lxnotadd.com&trade_type=NATIVE


        $para = [
            'body' => 'test',
            'out_trade_no' => '2017060912121210017892228529',
            'time_start'=>date('YmdHis'),
            'time_expire'=>date('YmdHis',time() + 600),
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 1,
            'trade_type' => 'NATIVE',
        ];

        $order = new Order();

        $order->out_trade_no = $para['out_trade_no'];

        $order->trade_status = 0;

        $order->seller_id = $this->gateway->getMchId();

        $order->total_amount = $para['total_fee'];

        $order->trade_no = '';

        $order->created_at = time();

        $order->payment = 'wechat';

        $order->subject = '';

        $order->save();

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
    }

    //回调
    public function webnotify(){

        $gateway = Omnipay::create('WechatPay');
        $gateway->setAppId('wx081bfce94ce71bfb');
        $gateway->setMchId('1268498801');
        $gateway->setApiKey('t4IYxcncB94TMAp5c0ZCkQKwjseDJBGA');

        $response = $gateway->completePurchase([
            'request_params' => file_get_contents('php://input')
        ])->send();

        if ($response->isPaid()) {
            //pay success
            Log::info('微信来调我了');
        }else{
            //pay fail
            Log::info('微信没有来骚扰我');
        }
    }


    //查询
    public function query(Array $para){

        $para = [
            'body' => 'test',
            'notify_url' => 'http://lxnotadd.com',
            'out_trade_no' => '201706091212121001789222',
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 3,
            'trade_type' => 'NATIVE',
        ];

        $response = $this->gateway->query($para)->send();


        $result = $response->isSuccessful();

        dd($response);


    }

    //退款
    public function refund(Array $para){
        $para = [
            'body' => 'test',
            'notify_url' => 'http://lxnotadd.com',
            'out_trade_no' => '201706091212121001789222',
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 1,
            'trade_type' => 'NATIVE',
            'out_refund_no'=>'126849880120170609121212',
            'refund_fee'=>1,
            'cert_path'=>storage_path('uploads/e57755b07bbb/fe1bd9d60a607941a4ca.pem'),
            'key_path'=>storage_path('uploads/317becefdd9a/77e78f5b31bc56bf7cd5.pem')
        ];

        $response = $this->gateway->refund($para)->send();

        dd($response);

        $result = $response->isSuccessful();

        dd($result);
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

