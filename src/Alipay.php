<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/22
 * Time: 18:01
 */
namespace Notadd\Payment;

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
    }

    public function getConfig($config){
        return $this->settings->get($config);
    }

    public function getGateWay($gatewayName)
    {
        $this->gateway = Omnipay::create($gatewayName ); 
        $this->gateway->setSignType('RSA2'); // RSA/RSA2/MD5
        $this->gateway->setAppId('2016080700187736'); //支付宝应用ID
        $this->gateway->setPrivateKey('MIIEpAIBAAKCAQEA0gpQKfMvHENcwQGHJjP3V+b99JYw21cLzHgG47MaJS29lhAcoSrU02s2o4TbtbGoRESx7LJAU810eAmqiGUhJzsDC0xgyhPr+ifgJzYIagaVF7xiDqV87HrHVsN+iSxBz8skGMV9xEmFrZKvlzizo5hUeRRj+m7HxU/v6gXlWJfVL2by7JKqj3eQysJ4FhghHoVWBw21Mb5KO4sR0ruWJbbiMY/+BVGMx8mmsSAeTX7msxZCCmjBLt3u3zuKf0/rC5kjyCnkjfpZ+CsN+EQQzg17OG8fAxbrWNrVv5sDvNZr/cvM4aIcPFX1zW7h9nKZcr4XnzvCZItI+AJeUvTkZwIDAQABAoIBAQCtrJVoeoqjxhd2B/tmXWXSK1Al4+J2G13ILh8UPE4MG+w9nwjtQutD/czqHLtHW6UU/fBZFXMXWDVL5EI8EdL+C0+TEg6eSSbAbCRuh8EhWlwBimVSUwgeyc72MSP57LxmQ0SPrw01Ns3wHfBPDDWNujFvBKs+skdBIb3xNfrT6wby4hmehcqigGS3uT/jRwHjahvN81hpU23X8WA8jUoD+n//5aEIuIQoMKidnZNcU/IH8ekPBNEq364d0SyF0BMl9mR5d8Cds4S5B4zoUscWCdzx/bw1FE477rzxbL1S1kfWlZW3y6C7ub4nubkq8SNSYGU44L/YmfQqQlQKUoAhAoGBAP3Ndy4A7fXv2yENt0wkPtztHZuv8dyeiqn1J3GXdzQQ+YrJW9eYl/mWABucicn4ZZj0p4XlkYfda1IjYGWILyFItTELpAeNXXcqSUHukwvqY+nAXhjE+OGYaNBmGKC0oKRGuoL/4KBBUbJhfN5aeje6xssjaTHCY7HkCotUTQqJAoGBANPb2gkwxPwWrEQWSEtNCfKYlHgPg5hrFc+1RJZssW6YDH1K6A8dP5qBC3DhEKkcph5Xs7/j9dNrnYV+V4diCqCiCBhXT/OFJu6hfi8+tP/lk20mkn4eN7i9zgOl8Ae1GIZzuSUCIOi0V3I7EfkoB4t913912fM4Kk7AMC54MftvAoGAbgW11nF8tpKF0axae11zCt6XaTg6hsMLpWWq9akNebqqEqn7cFir+Pw3i8CuUqmlkUrtnDPKhLVNosvHK/x/QdR9B4feUY/Mpq3kF/rOL+op6dkEcYRhx5oEReMcZf4nbU9j5wn5XD80pTlxogpi1OnY55KwNWvpP+J1V7TxuNECgYEA0qjTnzvdQ4UP+gvmCuopsA5D4zYZ9h7U61olPyYJMB0EnjkzkoPU0L7sL2u780xHafhvBamYsIdbdHfydO3p/1vejMWU/GI0Gih+nl/N/n27IFewZrOJWKtzBHGlwSQzpM3BFamOK5XXNgDCmGtTRUL2WwlGlPAOMYws3KgrmwUCgYAkiGPylq2sO6MZd8bTrDJtjpu36k4XAsPGbV0VlIr26snAcotrqh7EgaUu8PlYuWSwh8D28qx3yw43AHVetBpo1h1lXHY+4NEbErMTGM0uAMSJNDmo3oNpXZs4+QabTSZUk/YK894aSeX1CCG0GUfOnv7Cnl/qLrwrEh8MdTrpng==');//支付宝应用私钥
        $this->gateway->setAlipayPublicKey('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0gpQKfMvHENcwQGHJjP3V+b99JYw21cLzHgG47MaJS29lhAcoSrU02s2o4TbtbGoRESx7LJAU810eAmqiGUhJzsDC0xgyhPr+ifgJzYIagaVF7xiDqV87HrHVsN+iSxBz8skGMV9xEmFrZKvlzizo5hUeRRj+m7HxU/v6gXlWJfVL2by7JKqj3eQysJ4FhghHoVWBw21Mb5KO4sR0ruWJbbiMY/+BVGMx8mmsSAeTX7msxZCCmjBLt3u3zuKf0/rC5kjyCnkjfpZ+CsN+EQQzg17OG8fAxbrWNrVv5sDvNZr/cvM4aIcPFX1zW7h9nKZcr4XnzvCZItI+AJeUvTkZwIDAQAB');//支付宝应用公钥
//        $this->gateway->setSellerEmail($this->settings['seller_email']); //收款账户 email地址
        $this->gateway->setReturnUrl('http://pay.ibenchu.xyz:8080/webnotice');
        $this->gateway->setNotifyUrl('http://pay.ibenchu.xyz:8080/webnotice');
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
