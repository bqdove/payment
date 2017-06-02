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
use Notadd\Alipay\Facades\AlipayWeb;
use Notadd\Multipay\Handlers\GetAlipayconfHandler;
use Omnipay\Omnipay;

class Alipay
{
    protected $settings;

    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    public function getconfig($config){
        return $this->settings->get($config);
    }
    
    public function get_gate_way()
    {
          $data = $this->settings;
          $gateway = Omnipay::create( 'Alipay_LegacyExpress' );
          $gateway->setPartner($data ['partner_id']); //支付宝 PID
          $gateway->setKey( '' );  //支付宝 Key
          $gateway->setSellerEmail( $data['seller_email']); //收款账户 email
          $gateway->setReturnUrl( $data['return_url']);
          $gateway->setNotifyUrl( $data['notify_url'] );
    
          return $gateway;
     }

    /**
  *申请支付
  */

    public function pay($partner_id = null, $merchant_private_key = null, $method = 'alipay.trade.query', $charset = 'UTF-8', $sign_type = 'RSA2', $sign, $timestamp, $version = 1.0, $biz_content = null, $out_trade_no = 0)
    {
        $enabled = $this->settings->get('alipay_enabled'); //是否开启支付宝支付

        $partner_id = $this->settings->get('partner_id');//partner_id

        $merchant_private_key = $this->settings->get('merchant_private_key');//private_key

        $timestamp = new date("Y-m-d G-i-s", time());//format order time

        $biz_content = {
        };

	$options = [
        'partner_id' => $partner_id,
        'merchant_private_key' => $merchant_private_key
        // 'out_trade_no' => $tn, //生成唯一订单号
        // 'subject' => '', //订单标题
        // 'total_fee' => , //订单总金额
    ];

	// 获取支付网关
	$gateway = $this->get_gate_way();

	$response = $gateway->purchase($options)->send();

	$response->redirect();
    }

    /**
     * 异步&&同步通知
     */
    public function webNotify()
    {
        $gateway = $this->get_gate_way();
        $request = $gateway->completePurchase();
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


    public function refund($app_id, $method = "alipay.trade.refund", $charset = 'UTF-8', $sign_type = 'RSA2', $sign, $timestamp, $version = 1.0, $biz_content = null)
    {
        $partner_id = $this->settings->get('partner_id');

        $merchant_private_key = $this->settings->get('merchant_private_key');

        $timestamp = new date("Y-m-d G-i-s", time());//format order time

        $biz_content = {};

	$options = [
        'partner_id' => $partner_id,
        'merchant_private_key' => $merchant_private_key,
        // 'out_trade_no' => $tn, //生成唯一订单号
        // 'subject' => '', //订单标题
        // 'total_fee' => , //订单总金额
        'biz_content' => {};
	];

	// 获取支付网关
	$gateway = $this->get_gate_way();

	$response = $gateway->refund($options)->send();

	$response->redirect();
    }
}
