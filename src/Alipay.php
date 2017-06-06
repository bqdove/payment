<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/22
 * Time: 18:01
 */
namespace Notadd\Multipay;

use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Omnipay\Omnipay;

class Alipay
{
    protected $settings;

    protected $gateway;

    public function __construct()
    {
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
        $this->getGateWay();
    }

    public function getConfig($config){
        return $this->settings->get($config);
    }

    public function getGateWay($gatewayName)
    {
        $data = $this->settings;
        $this->gateway = Omnipay::create($gatewayName );
        $this->gateway->setSignType('RSA2'); // RSA/RSA2/MD5
        $this->gateway->setAppId($data ['app_id']); //支付宝应用ID
        $this->gateway->setPrivateKey($data['private_key']);//支付宝应用私钥
        $this->gateway->setAlipayPublicKey($data['public_key']);//支付宝应用公钥
        $this->gateway->setSellerEmail($data['seller_email']); //收款账户 email地址
        $this->gateway->setReturnUrl($data['return_url']);//
        $this->gateway->setNotifyUrl($data['notify_url']);

        return $this;
     }

    /**
     *申请支付
     */

    public function pay($method = 'alipay.trade.page.pay', $charset = 'UTF-8', $sign_type = 'RSA2', $version = 1.0)
    {
        $timestamp = new date("Y-m-d G-i-s", time());//format order time

        $request = $this->gateway->purchase();

        $request->setBizContent([
            'version'      => $version,
            'charset'      => $charset,
            'sign_type'    => $sign_type,
            'method'       => $method,
            'timestamp'    => $timestamp,
            'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
            'total_amount' => 0.01,
            'subject'      => 'test',
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ]);

        $response = $request->send();

        $response->redirect();
    }

    /**
     * 异步&&同步通知
     */
    public function webNotify()
    {
        $request = $this->gateway->completePurchase();
        $request->setParams(array_merge($_POST, $_GET)); //Don't use $_REQUEST for may contain $_COOKIE

        /**
         * @var AopCompletePurchaseResponse $response
         */
        try {
            $response = $request->send();

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
        } catch (Exception $e) {
            /**
             * Payment is not successful
             */
            die('你已经支付失败, 请稍候重试'); //The notify response
        }
    }
    
    /**
      *  查询接口
      */

    public function query($method = 'alipay.trade.query', $charset = 'UTF-8', $sign_type = 'RSA2', $version = 1.0)
    {

        $timestamp = new date("Y-m-d G-i-s", time());//format order time

        $request = $this->gateway->query();

        $request->setBizContent([
            'version'      => $version,
            'charset'      => $charset,
            'sign_type'    => $sign_type,
            'method'       => $method,
            'timestamp'    => $timestamp,
            'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
        ]);

        $response = $request->send();

        $response->redirect();
    }

    /**
    *退款接口
    */
    public function refund($app_id, $method = "alipay.trade.refund", $charset = 'UTF-8', $sign_type = 'RSA2', $sign, $timestamp, $version = 1.0, $biz_content = null)
    {

        $timestamp = new date("Y-m-d G-i-s", time());//format order time

        $request = $this->gateway->refund();

        $request->setBizContent([
            'version'      => $version,
            'charset'      => $charset,
            'sign_type'    => $sign_type,
            'method'       => $method,
            'timestamp'    => $timestamp,
            'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
            'refund_amount'=> 200,
            'refund_reason'=> 'something why refund',
        ]);

        $response = $request->send();

        $response->redirect();
    }

    /**
     *交易撤销接口
     *
     */
    public function cancel()
    {
        $timestamp = new date("Y-m-d G-i-s", time());//format order time

        $request = $this->gateway->cancel();

        $request->setBizContent([
            'version'      => $version,
            'charset'      => $charset,
            'sign_type'    => $sign_type,
            'method'       => $method,
            'timestamp'    => $timestamp,
            'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
        ]);

        $response = $request->send();

        $response->redirect();
    }

}
