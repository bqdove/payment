<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/24
 * Time: 11:54
 */
namespace Notadd\Multipay\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Passport\Abstracts\DataHandler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class GetAlipayconfHandler.
 */
class GetAlipayconfHandler extends DataHandler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * GetHandler constructor.
     *
     * @param Container $container
     * @param SettingsRepository $settings
     */
    public function __construct(Container $container, SettingsRepository $settings)
    {
        parent::__construct($container);
        $this->settings = $settings;
    }

    /**
     * Data for handler.
     *
     * @return array
     */
    public  function data()
    {
        return [
            'alipay_enabled'=> $this->settings->get('alipay.alipay_enabled',false),

            'alipay__gateway_new'=> $this->settings->get('alipay.alipay__gateway_new',''),
            
            '__https_verify_url'=> $this->settings->get('alipay.__https_verify_url'),
            
            '__http_verify_url'=> $this->settings->get('alipay.__https_verify_url'),

            'service'=> $this->settings->get('alipay.service'),
            
            '_input_charset'=> $this->settings->get('alipay._input_charset'),
            
            'sign_type'=> $this->settings->get('alipay.sign_type'),
            
            'payment_type'=> $this->settings->get('alipay.payment_type'),

            'version' => $this->settings->get('alipay.version'),

            //'biz_content'=>$this->settings->get('alipay.biz_content'),

            'app_id'=> $this->settings->get('alipay.app_id'),

            'partner_id' => $this->settings->get('alipay.partner_id'),

            'method' => $this->settings->get('alipay.method'),
            
            'merchant_private_key'=> $this->settings->get('alipay.merchant_private_key'),
            
            'notify_url'=> $this->settings->get('alipay.notify_url'),
            
            'return_url'=> $this->settings->get('alipay.return_url'),
            
            'seller_email'=> $this->settings->get('alipay.seller_email'),
            
            'out_trade_no'=> $this->settings->get('alipay.out_trade_no'),

            'product_code' => $this->settings->get('alipay.product_code'),

            'total_amount' => $this->settings->get('alipay.total_fee'),
            
            'subject'=> $this->settings->get('alipay.subject'),
            
            'total_fee'=> $this->settings->get('alipay.total_fee'),
            
            'body'=> $this->settings->get('alipay.body'),
            
            'it_b_pay'=> $this->settings->get('alipay.it_b_pay'),
            
            'show_url'=> $this->settings->get('alipay.show_url'),
            
            'anti_phishing_key'=> $this->settings->get('alipay.anti_phishing_key'),
            
            'exter_invoke_ip'=> $this->settings->get('alipay.exter_invoke_ip'),
            
            '_input_charset'=> $this->settings->get('alipay._input_charset'),
            
            'qr_pay_mode'=> $this->settings->get('alipay.qr_pay_mode')
        ] ;
    }
}
