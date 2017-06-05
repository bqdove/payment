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

class UnionPay
{
    protected $settings;

    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    public function getGateWay($gatewayname)
    {
            $gateway = Omnipay::create($gatewayname);
            $gateway->setMerId($this->settings->get('union.merId'));
            $gateway->setCertPath($this->settings->get('union.certPath'));
            $gateway->setCertPassword($this->settings->get('union.certPassword'));
            $gateway->setCertDir($this->settings->get('union.certDir'));
            $gateway->setReturnUrl($this->settings->get('union.returnUrl'));
            $gateway->setNotifyUrl($this->settings->get('union.notifyUrl'));

            return $gateway;
    }

    public function pay($merId, $transType, $orderId, $txnTime, $orderDesc, $txnAmt)
    {
            $order = [
                    'orderId'   => $orderId, //Your order ID
                    'txnTime'   => $txnTime, //Should be format 'YmdHis'
                    'orderDesc' => $orderDesc, //Order Title
                    'txnAmt'    => $txnAmt, //Order Total Fee
                    'merId' => $merId,//merId
                    'transType' => $transType// transtype
            ];

            $gateway = $this->getGateWay();

            $response = $gateway->purchase($order)->send();

            $response->getRedirectHtml(); //For PC/Wap
    }

    public function webNotify()
    {
            $gateway = $this->getGateWay();

            $gateway->setMerId($this->settings->get('union.merId'));

            $gateway->setCertDir($this->settings->get('union.certDir')); //The directory contain *.cer files

            $response = $gateway->completePurchase(['request_params'=>$_REQUEST])->send();

            if ($response->isPaid()) {
                //exit('支付成功！');
            }else{
                //exit('支付失败！');
            }
    }
    /**
      *  查询接口
      */
    public function query($merId, $transType, $orderId, $orderTime)
    {
            $order = [
                    'merId' => $merId,//merId
                    'transType' => $transType,// transtype
                    'orderId'   => $orderId, //Your order ID
                    'orderTime'   => $orderTime, //Should be format 'YmdHis'
            ];

            $gateway = $this->getGateWay();

            $response = $gateway->query($order)->send();
    }

    /**
    *
    *退款接口
    */

    public function refund($merId, $transType, $orderId, $orderTime, $totalFee)
    {
            $gateway = $this->getGateWay();

            $order = [
                    'merId'  => $merId,
                    'transType' => $transType,
                    'orderId' => $orderId, //Your site trade no, not union tn.
                    'orderTime' => $orderTime, //Order trade time
                    'txnAmt'  => $totalFee, //Order total fee
            ];

            $response = $gateway->refund($order)->send();

            var_dump($response->isSuccessful());

            var_dump($response->getData());
    }

}