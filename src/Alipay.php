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
use Notadd\Multipay\Handlers\GetAlipayconfHandler;

class Alipay
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    protected $gateway;
    /**
     * GetHandler constructor.
     *
     * @param Container $container
     * @param SettingsRepository $settings
     */

    public function __construct()
    {
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    public function getConfig($config){
        return $this->settings->get($config);
    }

    public function getGateWay($gatewayName)
    {
        $this->gateway = Omnipay::create($gatewayName );
        $this->gateway->setSignType($this->settings->get('alipay.sign_type')); // RSA/RSA2/MD5
        $this->gateway->setAppId($this->settings->get('alipay.app_id')); //支付宝应用ID
        $this->gateway->setPrivateKey($this->settings->get('alipay.private_key'));//支付宝应用私钥
        $this->gateway->setAlipayPublicKey($this->settings->get('alipay.public_key'));//支付宝应用公钥
//        $this->gateway->setSellerEmail($this->settings['seller_email']); //收款账户 email地址
        $this->gateway->setReturnUrl('http://pay.ibenchu.xyz:8080');
        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080');
        $this->gateway->sandbox();
        return $this;
     }

    /**
     * 申请支付
     */
    public function pay(Array $para)
    {
        $request = $this->gateway->purchase();

        $request->setBizContent($para);

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
     * 查询接口
     */
    public function query(Array $para)
    {

        $request = $this->gateway->query();

        $request->setBizContent($para);

        $response = $request->send();

        dd($response->data()['alipay_trade_query_response']);//get order information

    }

    /**
     * 退款接口
     */
    public function refund(Array $para)
    {
        $request = $this->gateway->refund();

        $request->setBizContent($para);

        $response = $request->send();

        dd($response->data()['alipay_trade_refund_response']);//get refund order information

    }

    /**
     * 交易撤销接口
     */
    public function cancel(Array $para)
    {
        $request = $this->gateway->cancel();

        $request->setBizContent($para);

        $response = $request->send();

        dd($response->data()['alipay_trade_refund_response']);//get cancel information
    }

}
