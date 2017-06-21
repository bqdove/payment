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

class Unionpay
{
    protected $settings;
    protected $gateway;

    //获取配置
    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }
    /**
     * 获取网关接口
     */
    public function getGateWay($gatewayname)
    {
        $this->gateway = Omnipay::create($gatewayname);
        $this->gateway->setMerId($this->settings->get('union.merId'));
        $this->gateway->setCertPath($this->settings->get('union.certPath'));
        $this->gateway->setCertPassword($this->settings->get('union.certPassword'));
        $this->gateway->setCertDir($this->settings->get('union.certDir'));
        $this->gateway->setReturnUrl('http://pay.ibenchu.xyz:8080/multipay/webnotify');
        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080/multipay/webnotify');
        return $this;
    }

    /**
     * 支付接口
     * @param $merId
     * @param $transType
     * @param $orderId
     * @param $txnTime
     * @param $orderDesc
     * @param $txnAmt
     */
    public function pay()
    {
        $para = [
                'version'=>$this->settings->get('unionpay.version'),
                'signMethod'=>$this->settings->get('unionpay.signMethod'),
                'encoding'=>$this->settings->get('unionpay.encoding'),
                'orderId'   => $orderId, //商户订单号
                'txnTime'   => $txnTime, //Should be format 'YmdHis'
                'orderDesc' => $orderDesc, //Order Title
                'txnAmt'    => $txnAmt, //Order Total Fee
                'currencyCode' => 156,//交易币种
                'txnType' => 01,//交易类型
        ];

        $response = $this->gateway->purchase($para)->send();

        $response->getRedirectHtml(); //For PC/Wap
    }
    /**
     * 回调方法接口
     */
    public function webNotify()
    {

        $gateway = Omnipay::create('UnionPay');
        $gateway->setMerId($this->settings->get('union.merId'));
        $gateway->setCertPath($this->settings->get('union.certPath'));
        $gateway->setCertPassword($this->settings->get('union.certPassword'));
        $gateway->setCertDir($this->settings->get('union.certDir'));
        $response = $gateway->completePurchase(['request_params'=>$_REQUEST])->send();

        if ($response->isPaid()) {
            /**
             * Payment is successful
             */
            die('success'); //The notify response should be 'success' only
        } else {
            /**
             * Payment is not successful
             */
            die('你已经支付失败, 请稍候重试'); //The notify response
        }
    }
    /**
     *查询接口
     */
    public function query(Array $para)
    {
//        $order = [
//            'merId' => $merId,//merId
//            'transType' => $transType,// transtype
//            'orderId' => $orderId, //Your order ID
//            'orderTime' => $orderTime, //Should be format 'YmdHis'
//        ];
        $response = $this->gateway->query($para)->send();

        var_dump($response->getData());
    }

    /**
     *
     * 退款接口
     */

    public function refund(Array $para)
    {
//        $order = [
//                'merId'  => $merId,
//                'transType' => $transType,
//                'orderId' => $orderId, //Your site trade no, not union tn.
//                'orderTime' => $orderTime, //Order trade time
//                'txnAmt'  => $totalFee, //Order total fee
//        ];

        $response = $this->gateway->refund($para)->send();

        var_dump($response->getData());
    }

    /**
     * 取消接口
     */

    public function cancel(Array $para)
    {
//        $order = [
//            'merId'  => $merId,
//            'transType' => $transType,
//            'orderId' => $orderId, //Your site trade no, not union tn.
//            'orderTime' => $orderTime, //Order trade time
//            'txnAmt'  => $totalFee, //Order total fee
//        ];

        $response = $this->gateway->consumeUndo($para)->send();

//        var_dump($response->isSuccessful());

        var_dump($response->getData());
    }

}