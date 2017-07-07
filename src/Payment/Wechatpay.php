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
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

class Wechatpay implements Payment
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
//        $this->gateway->setAppId('wx081bfce94ce71bfb');
        $this->gateway->setAppId($this->settings->get('wechat.app_id'));
//        $this->gateway->setMchId('1268498801');
        $this->gateway->setMchId($this->settings->get('wechat.mch_id'));
//        $this->gateway->setApiKey('t4IYxcncB94TMAp5c0ZCkQKwjseDJBGA');
        $this->gateway->setApiKey($this->settings->get('wechat.key'));

        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080/api/multipay/wechat/webnotify');

        return $this;
    }

    //支付接口
    public function pay()
    {
        $para = Container::getInstance()->make('request')->all();

        $order = new Order();

        $order->out_trade_no = $para['out_trade_no'];

        $order->trade_status = 0;

        $order->seller_id = $this->gateway->getMchId();

        $order->total_amount = $para['total_fee'];

        $order->trade_no = '';

        $order->created_at = time();

        $order->payment = 'wechat';

        $order->subject = $para['body'];

        $order->save();


        $response = $this->gateway->purchase($para)->send();


        $code_url = $response->getCodeUrl();

        $qrCode = new QrCode();
        $qrCode->setText($code_url)
            ->setWriterByName('png')
            ->setMargin(10)
            ->setEncoding('UTF-8')
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::LOW)
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
            ->setValidateResult(false);

        $qrcodeName = rand(1, 10000) . '.png';

        $qrCode->writeFile(__DIR__ . '/qrcode_temp/' . $qrcodeName);

        $str = file_get_contents(__DIR__ . '/qrcode_temp/' . $qrcodeName);

        $base64_qrcode = base64_encode($str);

        return ['base64' => $base64_qrcode, 'qrcode' => __DIR__ . '/qrcode_temp/' . $qrcodeName, 'type' => 'wechat'];
    }

    //回调
    public function webnotify()
    {

        $gateway = Omnipay::create('WechatPay');

        $gateway->setAppId($this->settings->get('wechat.app_id'));
        $gateway->setMchId($this->settings->get('wechat.mch_id'));
        $gateway->setApiKey($this->settings->get('wechat.key'));

        $response = $gateway->completePurchase([
            'request_params' => file_get_contents('php://input')
        ])->send();

        $xmlData = file_get_contents('php://input');

        $arrayData = $this->xmlToArray($xmlData);

        if ($response->isPaid()) {
            //pay success
            if ($order = Order::where('out_trade_no', $arrayData['out_trade_no'])->first()) {
                $order->total_amount = $arrayData['total_fee'] / 100;
                $order->trade_no = $arrayData['transaction_id'];
                $order->pay_way = $arrayData['trade_type'];
                $order->trade_status = 1;
                $optionArr = ['openid' => $arrayData['openid']];
                $json = json_encode($optionArr);
                $order->options = $json;
                $order->save();
                die('success');
            }
        } else {
            return false;
        }
    }


    //查询
    public function query()
    {

        $para = Container::getInstance()->make('request')->all();

        if ($para['out_trade_no'] || $para['transaction_id']) {
            $response = $this->gateway->query($para)->send();
        } else {
            return 402;
        }
        if ($response->isSuccessful()) {
            return $response->getData();
        }

    }

    //退款
    public function refund()
    {
        $para = Container::getInstance()->make('request')->all();

        $out_refund_no = (string)($this->settings->get('wechat.mch_id'));
        $out_refund_no .= date('YmdHis', time());
        $para = $para + ['out_refund_no' => $out_refund_no,
                'cert_path' => $this->settings->get('wechat.cert'),
                'key_path' => $this->settings->get('wechat.cert_key')];

        if (($para['out_trade_no'] || $para['transaction_id']) && $para['refund_fee'] && $para['total_fee']) {
            $response = $this->gateway->refund($para)->send();

            if ($response->isSuccessful()) {
                return $response->getData();
            } else {
                return $response->getData();
            }
        } else {
            return 402;
        }
    }

    //取消
    public function cancel()
    {
        $para = [
            'body' => 'test',
            'notify_url' => 'http://pay.ibenchu.xyz:8080/api/multipay/wechat/webnotify',
            'out_trade_no' => '201706091212121006',
            'spbill_create_ip' => '36.45.175.53',
            'total_fee' => 1,
            'trade_type' => 'NATIVE',
        ];
        $response = $this->gateway->close($para)->send();
    }

    //xml=>array
    private function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

}

