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
        $this->gateway->setReturnUrl('http://pay.ibenchu.xyz:8080/api/multipay/alipay/notify');
        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080/api/multipay/alipay/webnotify');
        $this->gateway->sandbox();
        return $this;
     }

    /**
     * 申请支付
     */
    public function pay()
    {
        $para = Container::getInstance()->make('request')->all();

        $order = new Order();
        $order->out_trade_no = $para['out_trade_no'];//获取商户订单号,微信数据库需要此数据
        $order->trade_status = 0;//交易状态初始化为未支付
        $order->seller_id = $this->settings->get('alipay.app_id');//卖家ID
        $order->total_amount = $para['total_amount'];//交易金额
        $order->trade_no = '';//平台返回的订单号
        $order->created_at = time();//订单创建时间
        $order->payment = 'alipay';//支付平台
        $order->subject = $para['subject'];//订单主题
        $order->save();

        $request = $this->gateway->purchase();

        $request->setBizContent($para);

        $response = $request->send();

        $redirectUrl = $response->getRedirectUrl();

        return ['url' => $redirectUrl, 'type' => 'alipay'];
    }

    /**
     * 异步通知
     */
    public function webNotify()
    {
        $arrayData = Container::getInstance()->make('request')->all();
        $gateway = Omnipay::create('Alipay_AopPage');
        $gateway->setSignType($this->settings->get('alipay.sign_type')); // RSA/RSA2/MD5
        $gateway->setAppId($this->settings->get('alipay.app_id')); //支付宝应用ID
        $gateway->setPrivateKey($this->settings->get('alipay.private_key'));//支付宝应用私钥
        $gateway->setAlipayPublicKey('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsKgDn9nrnnpfQbhu7AG55wADL+3plJOtD9ty7i2c2FxgCNvhRSiKRHuv0C1yABI47H2dkl180frRWLsVWhy1RZ/F+xQwj2VK+0HZu8eAuwn1/rGdogLRFSIzWiYTd6d24QLdpzdtKbw9w5LJfRLnfu2nhLNkexXCasR+BdyurP0XCiqSu75IXNU4ZhXX338fhh+lQbHS4DVczz9SrhTNhhegk9VJgLdqvAIffnE9NSSE00onweDlPe6Ygsfxdtb0BHKgcBKHrhQTeYL1vvHID1zPBZQfs/JTDn4CfE130IiTyPVyRyH5SJijcMrue6EkYgARNwADLMu+DG8IzrCx6QIDAQAB');//支付宝应用公钥

        /**
         * @var AopCompletePurchaseResponse $response
         */
        try {
            $response = $gateway->completePurchase(['params' => array_merge($_POST)])->send();

            if ($response->isPaid()) {
                /**
                 * Payment is successful
                 */
                if($order = Order::where('out_trade_no', $_POST['out_trade_no'])->first())
                {
                    $order->total_amount = $_POST['total_amount'];
                    $order->trade_no = $_POST['trade_no'];
                    $order->seller_id = $_POST['seller_id'];
                    $order->trade_status = 1;
                    $order->payment = 'alipay';
                    $order->created_at = $_POST['gmt_create'];
                    $order->subject = $_POST['subject'];
                    $optionArr = ['buyer_id' => $_POST['buyer_id'],'invoice_amount' => $_POST['invoice_amount'],'fund_bill_list'=>$_POST['fund_bill_list']];
                    $json = json_encode($optionArr);
                    $order->options = $json;
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
     * 同步回调
     */
    public function returnNotify()
    {
        return $_GET;
    }


    /**
     * 查询接口
     */
    public function query()
    {
        $para = Container::getInstance()->make('request')->all();

        $request = $this->gateway->query();

        $request->setBizContent($para);

        $response = $request->send();

        return $response->data()['alipay_trade_query_response'];//get order information

    }

    /**
     * 退款接口
     */
    public function refund()
    {
        $para = Container::getInstance()->make('request')->all();

        $request = $this->gateway->refund();

        $request->setBizContent($para);

        $response = $request->send();

        if ($para['out_trade_no'] || $para['trade_id'] && $para['total_amount']){
            if ($response->isSuccessful())
            {
                return $response->data()['alipay_trade_refund_response'];//get refund order information
            }else{
                return $response->data()['alipay_trade_refund_response'];
            }
        }else{
            return 402;
        }
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
