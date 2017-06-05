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
<<<<<<< HEAD
=======

>>>>>>> 14d478de30da9ed924a313ee434c8b8dbc1a5f2f

class UnionPay
{
    protected $settings;
<<<<<<< HEAD
    protected $gateway;

=======

    protected $gateway;
    //获取配置
>>>>>>> 14d478de30da9ed924a313ee434c8b8dbc1a5f2f
    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

<<<<<<< HEAD
    public function getGateWay($gatewayname)
    {
            $gateway = Omnipay::create($gatewayname);
            $gateway->setMerId($this->settings->get('union.merId'));
            $gateway->setCertPath($this->settings->get('union.certPath'));
            $gateway->setCertPassword($this->settings->get('union.certPassword'));
            $gateway->setCertDir($this->settings->get('union.certDir'));
            $gateway->setReturnUrl($this->settings->get('union.returnUrl'));
            $gateway->setNotifyUrl($this->settings->get('union.notifyUrl'));
=======
    public function getGateway($gateway)
    {
            $this->gateway = Omnipay::create($gateway);
            $this->gateway->setMerId($this->settings->get('union.merId'));
            $this->gateway->setCertPath($this->settings->get('union.certPath'));
            $this->gateway->setCertPassword($this->settings->get('union.certPassword'));
            $this->gateway->setCertDir($this->settings->get('union.certDir'));
            $this->gateway->setReturnUrl($this->settings->get('union.returnUrl'));
            $this->gateway->setNotifyUrl($this->settings->get('union.notifyUrl'));
>>>>>>> 14d478de30da9ed924a313ee434c8b8dbc1a5f2f

            return $this;
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

<<<<<<< HEAD
            $response = $gateway->purchase($order)->send();
=======
            $response = $this->gateway->purchase($order)->send();
>>>>>>> 14d478de30da9ed924a313ee434c8b8dbc1a5f2f

            $response->getRedirectHtml(); //For PC/Wap
    }

    public function webNotify()
    {
<<<<<<< HEAD
            $gateway->setMerId($this->settings->get('union.merId'));
=======
            $this->gateway->setMerId($this->settings->get('union.merId'));
>>>>>>> 14d478de30da9ed924a313ee434c8b8dbc1a5f2f

            $this->gateway->setCertDir($this->settings->get('union.certDir')); //The directory contain *.cer files

            $response = $this->gateway->completePurchase(['request_params'=>$_REQUEST])->send();

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

<<<<<<< HEAD

            $response = $gateway->query($order)->send();
            $response->isSuccessful();
=======
            $response = $this->gateway->query($order)->send();
>>>>>>> 14d478de30da9ed924a313ee434c8b8dbc1a5f2f
    }

    /**
    *
    *退款接口
    */

    public function refund($merId, $transType, $orderId, $orderTime, $totalFee)
    {
            $order = [
                    'merId'  => $merId,
                    'transType' => $transType,
                    'orderId' => $orderId, //Your site trade no, not union tn.
                    'orderTime' => $orderTime, //Order trade time
                    'txnAmt'  => $totalFee, //Order total fee
            ];

            $response = $this->gateway->refund($order)->send();

            var_dump($response->isSuccessful());

            var_dump($response->getData());
    }

}