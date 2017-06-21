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
use Notadd\Multipay\Models\Order;
use Illuminate\Support\Facades\Log;

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
        $this->gateway->setReturnUrl('http://pay.ibenchu.xyz:8080/api/multipay/alipay/webnotify');
        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080/api/multipay/alipay/webnotify');
        $this->gateway->sandbox();
        return $this;
     }

    /**
     * 申请支付
     */
    public function pay(Array $para)
    {
        $order = new Order();

        $order->out_trade_no = $para['out_trade_no'];

        $order->trade_status = 0;

        $order->seller_id = $para['app_id'];

        $order->total_amount = $para['total_amount'];

        $order->trade_no = '';

        $order->created_at = time();

        $order->payment = 'alipay';

        $order->subject = $para['subject'];

        $order->save();

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
        Log::info('test');
        Log::info(array_merge($_POST, $_GET));
        $arrayData = array_merge($_POST, $_GET);
        $gateway = Omnipay::create('Alipay_AopPage');
        $gateway->setSignType($this->settings->get('alipay.sign_type')); // RSA/RSA2/MD5
        $gateway->setAppId($this->settings->get('alipay.app_id')); //支付宝应用ID
        $gateway->setPrivateKey($this->settings->get('alipay.private_key'));//支付宝应用私钥
        $gateway->setAlipayPublicKey($this->settings->get('alipay.public_key'));//支付宝应用公钥

        $request = $gateway->completePurchase();

        Log::info('a');

        $request->setParams(array_merge($_POST, $_GET)); //Don't use $_REQUEST for may contain $_COOKIE

        Log::info('b');
        /**
         * @var AopCompletePurchaseResponse $response
         */
        try {
            Log::info('c');

            $response = $request->send();

            Log::info('1');
            if ($response->isPaid()) {
                /**
                 * Payment is successful
                 */
                Log::info('2');

                if($order = Order::where('out_trade_no', $arrayData['out_trade_no'])->first())
                {
                    Log::info('3');

                    $order->out_trade_no = $arrayData['out_trade_no'];
                    $order->total_amount = $arrayData['total_amount'];
                    $order->trade_no = $arrayData['trade_no'];
                    $order->seller_id = $arrayData['seller_id'];
                    $order->trade_status = 1;
                    $order->payment = 'alipay';
                    $order->created_at = $arrayData['timestamp'];
                    $order->subject = 'phone';
                    $order->pay_way = 'test';
                    $order->save();
                    die('success'); //The notify response should be 'success' only
                }
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
