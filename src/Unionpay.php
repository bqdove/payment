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

public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);

}
    //获取配置
public function getconfig($config){
        return $this->settings->get($config);
}

public function pay()
{
        $gateway    = Omnipay::create('UnionPay_Express');
        $gateway->setMerId($config['merId']);
        $gateway->setCertPath($config['certPath']); // .pfx file
        $gateway->setCertPassword($config['certPassword']);
        $gateway->setReturnUrl($config['returnUrl']);
        $gateway->setNotifyUrl($config['notifyUrl']);

        $order = [
                'orderId'   => date('YmdHis'), //Your order ID
                'txnTime'   => date('YmdHis'), //Should be format 'YmdHis'
                'orderDesc' => 'My order title', //Order Title
                'txnAmt'    => '100', //Order Total Fee
        ];

        $response = $gateway->purchase($order)->send();

        $response->getRedirectHtml(); //For PC/Wap
}

public function webNotify()
{
        $gateway    = Omnipay::create('UnionPay_Express');
        $gateway->setMerId($config['merId']);
        $gateway->setCertDir($config['certDir']); //The directory contain *.cer files
        $response = $gateway->completePurchase(['request_params'=>$_REQUEST])->send();
        if ($response->isPaid()) {
            //pay success
        }else{
            //pay fail
        }
}

public function refund()
{
          $response = $gateway->refund([
          'orderId' => '20150815121214', //Your site trade no, not union tn.
          'txnTime' => '20150815121214', //Order trade time
          'txnAmt'  => '200', //Order total fee
          ])->send();

          var_dump($response->isSuccessful());
          var_dump($response->getData());
}

}