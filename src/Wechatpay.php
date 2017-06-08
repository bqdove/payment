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

class Wechatpay
{
    protected $settings;
    protected $gateway;
    protected $gatewayName;
    
    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    /*
    * 获取支付网关
    */
    public function getGateWay($gatewayname){
        $this->gatewayName = $gatewayname;
        $this->gateway = Omnipay::create($gatewayname);
        $this->gateway->setAppId('wx2dd40b5b1c24a960');
        $this->gateway->setMchId(1235851702);
        return $this;
    }

    public function pay(Array $para){

//        $params = [
//            'sign' => '6F7E51B5C0324D66776127EFDC2F506F',
//            'body' =>'Iphone8',
//            'nonce_type'=> md5(uniqid()),
//            'out_trade_no' => date('YmdHis').mt_rand(1000,9999),
//            'total_fee' => 10,
//            'spbill_create_ip' => '172.19.0.1',
//            'notify_url' =>'http://lxnotadd.com/api/multipay/pay',
//            'trade_type'=>'NATIVE',
//            'product_id'=> 12235413214070356458058
//        ];
        $response = $this->gateway->purchase($para)->send();

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

            echo "success";
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

    //取消

    public function cancel($out_trade_no)
    {
        $response = $this->gateway->close([
            'out_trade_no' => $out_trade_no, //The merchant trade no
        ])->send();

        var_dump($response->isSuccessful());
        var_dump($response->getData());
    }

}