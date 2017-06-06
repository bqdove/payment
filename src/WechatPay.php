<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/6/1
 * Time: 22:37
 */

namespace Notadd\Multipay;
use Omnipay\Omnipay;
use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Illuminate\Http\Request;
use Omnipay\WechatPay\Helper;
use Omnipay\WechatPay\Message\CreateOrderRequest;
use Omnipay\WechatPay\Message\RefundOrderRequest;

class WechatPay
{
    protected $settings;
    protected $gateway;
    protected $gatewayName;
    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
        $this->getGateWay();
    }


<<<<<<< HEAD
=======
    public function uploadcert(Request $request){
        $path ='../storage/cert/'.date('Ymd');
        $filename = $_FILES['cert']['name'];
        $request->file('cert')->move($path,$filename);
    }

>>>>>>> c13e2707bafe70d2745da748b75341604e52f95d
    public function getData()
    {
        $order = new CreateOrderRequest();
        //创建订单配置
        $data = array(
            'appid'            => $order->getAppId(),
            'mch_id'           => $order->getMchId(),
            'device_info'      => $order->getDeviceInfo(),
            'body'             => $order->getBody(),
            'detail'           => $order->getDetail(),
            'attach'           => $order->getAttach(),
            'out_trade_no'     => $order->getOutTradeNo(),
            'fee_type'         => $order->getFeeType(),
            'total_fee'        => $order->getTotalFee(),
            'spbill_create_ip' => $order->getSpbillCreateIp(),
            'time_start'       => $order->getTimeStart(),
            'time_expire'      => $order->getTimeExpire(),
            'goods_tag'        => $order->getGoodsTag(),
            'notify_url'       => $order->getNotifyUrl(),
            'trade_type'       => $order->getTradeType(),
            'limit_pay'        => $order->getLimitPay(),
            'openid'           => $order->getOpenId(),
            'nonce_str'        => md5(uniqid()),
        );
        $data = array_filter($data);

        $data['sign'] = Helper::sign($data, $order->getApiKey());

        return $data;
    }

    //退款订单配置
    public function getrefundData()
    {

        $refund = new RefundOrderRequest();

        $data = array (
            'appid'           => $refund->getAppId(),
            'mch_id'          => $refund->getMchId(),
            'device_info'     => $refund->getDeviceInfo(),
            'transaction_id'  => $refund->getTransactionId(),
            'out_trade_no'    => $refund->getOutTradeNo(),
            'out_refund_no'   => $refund->getOutRefundNo(),
            'total_fee'       => $refund->getTotalFee(),
            'refund_fee'      => $refund->getRefundFee(),
            'refund_fee_type' => $refund->getRefundType(),
            'op_user_id'      => $refund->getOpUserId() ?: $refund->getMchId(),
            'nonce_str'       => md5(uniqid()),
        );

        $data = array_filter($data);

        $data['sign'] = Helper::sign($data, $refund->getApiKey());

        return $data;
    }

    /*
     * 获取支付网关
     */
    public function getGateWay($gatewayname){
        $this->gatewayName = $gatewayname;
        $this->gateway = Omnipay::create($gatewayname);
        $this->gateway->setAppId($this->settings->get('wechat.app_id'));
        $this->gateway->setMchId($this->settings->get('wechat.mch_id'));
        $this->gateway->setApiKey($this->settings->get('wechat.key'));

        return $this;
    }


    public function pay(){
        $params = [
            'sign' => $this->getData($data['sign']),
            'body' => $this->getData($data['body']),
            'nonce_type'=> $this->getData($data['nonce_type']),
            'out_trade_no' => $this->getData($data['out_trade_no']),
            'total_fee' => $this->getData($data['total_fee']),
            'spbill_create_ip' => $this->getData($data['spbill_create_ip']),
            'notify_url' =>$this->getData($data['notify_url']),
            'trade_type'=>$this->getData($data['trade_type'])
        ];
        $para1 = [
            'open_id' => $this->getData($data['open_id'])
        ];
        $para2 = [
            'product_id'=>$this->getData($data['product_id'])
        ];
        $options1 = $params + $para1;
        $options2 = $params + $para2;
        if($this->gatewayName == 'JSAPI'){
            $response = $this->gateway->purchase($options1)->send();
        }elseif($this->gatewayName == 'NATIVE'){
            $response = $this->gateway->purchase($options2)->send();
        }else{
            $response = $this->gateway->purchase($params)->send();
        }
        $response->isSuccessful();

    }

    //回调通知
    public function webNotify($nonce_str,$sign,$result_code,$openid,$trade_type,$bank_type,$total_fee,$cash_fee_type,$transaction_id,$out_trade_no,$time_end){
        if('return_code'=='SUCCESS'){
            $options=[
                'nonce_str'=>$nonce_str,
                'sign'=>$sign,
                'result_code'=>$result_code,
                'openid'=>$openid,
                'trade_type'=>$trade_type,
                'bank_type'=>$bank_type,
                'total_fee'=>$total_fee,
                'cash_fee_type'=>$cash_fee_type,
                'transaction_id'=>$transaction_id,
                'out_trade_no'=>$out_trade_no,
                'time_end'=>$time_end
            ];
        }
        $request = $this->gateway->completePurchase($options);
        if ( $request->isPaid()) {

            echo "支付成功";
        } else {
            echo "支付失败";
        }
    }
    //查询
    public function query($transaction_id,$nonce_str,$sign){
        $options = [
            'transaction_id'=>$transaction_id,
            '$nonce_str'=>$nonce_str,
            'sign'=>$sign
        ];
        $response = $this->gateway->query($options)->send();
        $response->isSuccessful();
    }
    //退款
    public function refund(){
        $this->gateway->setCertPath($this->settings->get('wechat.certpath'));
        $this->gateway->setKeyPath($this->settings->get('wechat.keypath'));
        $response = $this->gateway->refund([
            'nonce_str'=>$this->getrefundData($data['nonce_str']),
            'transaction_id'=>$this->getrefundData($data['transaction_id']),
            'sign'=>$this->getrefundData($data['sign']),
            'out_refund_no'=>$this->getrefundData($data['out_refund_no']),
            'total_fee'=>$this->getrefundData($data['total_fee']),
            'refund_fee'=>$this->getrefundData($data['refund_fee'])
        ])->send();
        $response->isSuccessful();
    }

}