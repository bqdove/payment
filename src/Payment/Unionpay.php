<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/23
 * Time: 18:18
 */

namespace Notadd\Multipay;

use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Illuminate\Container\Container;
use Omnipay\Omnipay;
use Notadd\Multipay\Models\Order;


class Unionpay implements Payment
{
    protected $settings;
    protected $gateway;

    //获取配置
    public function __construct()
    {
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    /**
     * 获取网关接口
     */
    public function getGateWay($gatewayname)
    {
        $this->gateway = Omnipay::create($gatewayname);
        $this->gateway->setMerId($this->settings->get('union.merId'));
        $this->gateway->setCertPath($this->settings->get('union.cert'));
        $this->gateway->setCertPassword($this->settings->get('union.certPassword'));
        $this->gateway->setCertDir($this->settings->get('union.certDir'));
        $this->gateway->setReturnUrl('http://pay.ibenchu.xyz:8080/multipay');
        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080/multipay/webnotify');
        return $this;
    }

    /**
     * 支付接口
     *
     */
    public function pay()
    {
        $para = [
            'version' => $this->settings->get('unionpay.version'),
            'signMethod' => $this->settings->get('unionpay.signMethod'),
            'encoding' => $this->settings->get('unionpay.encoding'),
            'orderId' => '201706211420351000', //商户订单号
            'txnTime' => '20170621142035', //订单发送时间
            'txnAmt' => 1, //交易金额（分）
            'certId' => '',//证书ID
            'currencyCode' => 156,//交易币种
            'txnType' => 01,//交易类型
            'bizType' => 000501,//产品类型
            'channelType' => 07,//渠道类型
        ];

        $order = new Order();

        $order->out_trade_no = $para['orderId'];

        $order->trade_status = 0;

        $order->seller_id = $this->gateway->getMerId();

        $order->total_amount = $para['txnAmt'];

        $order->trade_no = '';

        $order->created_at = time();

        $order->payment = 'union';

        $order->save();

        $response = $this->gateway->purchase($para)->send();

        $response->getRedirectHtml(); //For PC/Wap
    }

    /**
     * 回调方法接口
     */
    public function webNotify()
    {
        $arrayData = array_merge($_POST);
        $gateway = Omnipay::create('UnionPay');
        $gateway->setMerId($this->settings->get('union.merId'));
        $gateway->setCertPath($this->settings->get('union.cert'));
        $gateway->setCertPassword($this->settings->get('union.certPassword'));
        $gateway->setCertDir($this->settings->get('union.certDir'));
        $response = $gateway->completePurchase(['params' => array_merge($_POST)])->send();
        if ($response->isPaid()) {
            /**
             * Payment is successful
             */
            if ($order = Order::where('out_trade_no', $_POST['out_trade_no'])->first()) {
                $order->total_amount = $_POST['total_amount'];
                $order->trade_no = $_POST['trade_no'];
                $order->seller_id = $_POST['seller_id'];
                $order->trade_status = 1;
                $order->payment = 'union';
                $order->created_at = $_POST['gmt_create'];
                $order->save();
                die('success');
            }
        } else {
            /**
             * Payment is not successful
             */
            die('你已经支付失败, 请稍候重试');
        }
    }

    /**
     *查询接口
     */
    public function query()
    {
        $para = array_merge($_POST);
        if ($para['orderId'] || $para['trade_no']) {
            $response = $this->gateway->query($para)->send();
        } else {
            return ['code' => '500', 'msg' => '请传入orderId 或者 trade_no'];
        }
        if ($response->successful()) {
            $response->getData();
        }
    }

    /**
     *
     * 退款接口
     */

    public function refund()
    {
        $para = array_merge($_POST);
        if ($para['orderId'] || $para['trade_no']) {
            $response = $this->gateway->refund($para)->send();
        } else {
            return ['code' => '500', 'msg' => '请传入orderId 或者 trade_no'];
        }
        if ($response->successful()) {
            $response->getData();
        }
    }

    /**
     * 取消接口
     */

    public function cancel()
    {
        $para = array_merge($_POST);
        if ($para['orderId'] || $para['trade_no']) {
            $response = $this->gateway->cancel($para)->send();
        } else {
            return ['code' => '500', 'msg' => '请传入orderId 或者 trade_no'];
        }
        if ($response->successful()) {
            $response->getData();
        }
    }
}