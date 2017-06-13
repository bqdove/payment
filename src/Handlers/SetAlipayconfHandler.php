<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/24
 * Time: 11:54
 */

namespace Notadd\Payment\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Passport\Abstracts\SetHandler as AbstractSetHandler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class AlipayHandler.
 */
class SetAlipayconfHandler extends AbstractSetHandler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * SetHandler constructor.
     *
     * @param \Illuminate\Container\Container                         $container
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $settings
     */
    public function __construct(
        Container $container,
        SettingsRepository $settings
    ) {
        parent::__construct($container);
        $this->settings = $settings;
    }

    /**
     * Data for handler.
     *
     * @return array
     */
    public function data()
    {
        return $this->settings->all()->toArray();
    }

    /**
     * Errors for handler.
     *
     * @return array
     */


    /**
     * Execute Handler.
     *
     * @return bool
     */
    public function execute()
    {
        $this->settings->set('alipay.enabled',  $this->request->input('alipay_enabled'));

        $this->settings->set('alipay.__gateway_new',  'https://mapi.alipay.com/gateway.do?');
        
        $this->settings->set('alipay.__https_verify_url',  'https://mapi.alipay.com/gateway.do?service=notify_verify&');
        
        $this->settings->set('alipay.__http_verify_url',  'http://notify.alipay.com/trade/notify_query.do?');
        
        $this->settings->set('alipay.service',  'create_direct_pay_by_user');
        
        $this->settings->set('alipay._input_charset',  'UTF-8');
        
        $this->settings->set('alipay.sign_type',  'MD5');
        
        $this->settings->set('alipay.payment_type',  1);
        
        $this->settings->set('alipay.version', 1.0);
        
        // $this->settings->set('alipay.biz_content')
        
        $this->settings->set('alipay.app_id',  $this->request->input('app_id'));
        
        $this->settings->set('alipay.method',  $this->request->input('method'));
        
        $this->settings->set('alipay.partner_id',  $this->request->input('partner_id'));
        
        $this->settings->set('alipay.merchant_private_key', $this->request->input('merchant_private_key'));
        
        $this->settings->set('alipay.alipay_public_key', $this->request->input('alipay_public_key'));
        
        $this->settings->set('alipay.service',  $this->request->input('alipay_service'));
        
        $this->settings->set('alipay.partner',  $this->request->input('alipay_partner'));
        
        $this->settings->set('alipay.payment_type',  $this->request->input('alipay_payment_type'));

        $this->settings->set('alipay.notify_url',  $this->request->input('alipay_notify_url'));
        
        $this->settings->set('alipay.return_url',  $this->request->input('alipay_return_url'));
        
        $this->settings->set('alipay.seller_email',  $this->request->input('alipay_seller_email'));
        
        $this->settings->set('alipay.out_trade_no',  $this->request->input('alipay_out_trade_no'));

        $this->settings->set('alipay.product_code',  $this->request->input('product_code'));

        $this->settings->set('alipay.total_amount', $this->request->input('total_amount'));

        $this->settings->set('alipay.subject',  $this->request->input('alipay_subject'));
        
        $this->settings->set('alipay.body',  $this->request->input('alipay_body'));
        
        $this->settings->set('alipay.total_fee',  $this->request->input('alipay_total_fee'));
        
        $this->settings->set('alipay.it_b_pay',  $this->request->input('alipay_it_b_pay'));
        
        $this->settings->set('alipay.show_url',  $this->request->input('alipay_show_url'));
        
        $this->settings->set('alipay.anti_phishing_key',  $this->request->input('alipay_anti_phishing_key'));
        
        $this->settings->set('alipay.exter_invoke_ip',  $this->request->input('alipay_exter_invoke_ip'));
        
        $this->settings->set('alipay._input_charset',  $this->request->input('alipay_input_charset'));
        
        $this->settings->set('alipay.qr_pay_mode',  $this->request->input('alipay_qr_pay_mode'));

        return true ;
    }


}
