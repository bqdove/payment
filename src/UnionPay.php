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
    protected $gateway;

    //获取配置
    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
        $this->getGateWay();
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
        $this->gateway->setReturnUrl($this->settings->get('union.returnUrl'));
        $this->gateway->setNotifyUrl($this->settings->get('union.notifyUrl'));

        return $this;
    }
    /**
     *上传证书
     */
    public function uploadcert(Request $request){
        $path ='../storage/cert/'.date('Ymd');
        $filename = $_FILES['cert']['name'];
        $request->file('cert')->move($path,$filename);
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

            $response = $this->gateway->purchase($order)->send();

            $response->getRedirectHtml(); //For PC/Wap
    }
    /**
     * 回调方法接口
     */
    public function webNotify($orderDesc,$orderId,$txnTime)
    {

            $response = $this->gateway->completePurchase(['request_params'=>$_REQUEST])->send();

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
    public function query($merId, $transType, $orderId, $orderTime)
    {
        $order = [
            'merId' => $merId,//merId
            'transType' => $transType,// transtype
            'orderId' => $orderId, //Your order ID
            'orderTime' => $orderTime, //Should be format 'YmdHis'
        ];
        $response = $this->gateway->query($order)->send();
        $response->isSuccessful();
    }

    /**
     *
     * 退款接口
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

    /**
     * 取消接口
     */

    public function cancel($merId, $transType, $orderId, $orderTime, $totalFee)
    {
        $order = [
            'merId'  => $merId,
            'transType' => $transType,
            'orderId' => $orderId, //Your site trade no, not union tn.
            'orderTime' => $orderTime, //Order trade time
            'txnAmt'  => $totalFee, //Order total fee
        ];

        $response = $this->gateway->consumeUndo($order)->send();

        var_dump($response->isSuccessful());

        var_dump($response->getData());
    }

}