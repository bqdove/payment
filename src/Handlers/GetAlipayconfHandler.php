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
    public static function data()
    {
        return [
            'alipay_enabled'=> $this->settings->get('alipay.alipay_enabled',false),
            'alipay.__gateway_new'=> $this->settings->get('alipay.alipay__gateway_new');
            'alipay.__https_verify_url'=> $this->settings->get('alipay.__https_verify_url');
            'alipay.__http_verify_url'=> $this->settings->get('alipay.__https_verify_url');
            'alipay.service'=> $this->settings->get('alipay.service');
            'alipay._input_charset'=> $this->settings->get('alipay._input_charset');
            'sign_type'=> $this->settings->get('alipay.sign_type');
            'payment_type'=> $this->settings->get('alipay.payment_type');
            'app_id'=> $this->settings->get('alipay.app_id', ''),
            'merchant_private_key'=> $this->settings->get('alipay.merchant_private_key',''),
            'alipay_public_key'=> $this->settings->get('alipay.alipay_public_key',''),
            'alipay.enabled'=> $this->settings->get('alipay_enabled'));
            'alipay.partner_id'=> $this->settings->get('partner_id'));
            'alipay.app_id'=> $this->setttings->get('app_id'));
            'alipay.merchant_private_key'=> $this->settings->get('merchant_private_key'));
            'alipay.alipay_public_key'=> $this->settings->get('alipay_public_key'));
            'alipay.service'=> $this->settings->get('alipay_service'));
            'alipay.partner'=> $this->settings->get('alipay_partner'));
            'alipay.payment_type'=> $this->settings->get('alipay_payment_type'));
            'alipay.notify_url'=> $this->settings->get('alipay_notify_url'));
            'alipay.return_url'=> $this->settings->get('alipay_return_url'));
            'alipay.seller_email'=> $this->settings->get('alipay_seller_email'));
            'alipay.out_trade_no'=> $this->settings->get('alipay_out_trade_no'));
            'alipay.subject'=> $this->settings->get('alipay_subject'));
            'alipay.total_fee'=> $this->settings->get('alipay_total_fee'));
            'alipay.body'=> $this->settings->get('alipay_body'));
            'alipay.it_b_pay'=> $this->settings->get('alipay_it_b_pay'));
            'alipay.show_url'=> $this->settings->get('alipay_show_url'));
            'alipay.anti_phishing_key'=> $this->settings->get('alipay_anti_phishing_key'));
            'alipay.exter_invoke_ip'=> $this->settings->get('alipay_exter_invoke_ip'));
            'alipay._input_charset'=> $this->settings->get('alipay_input_charset'));
            'alipay.qr_pay_mode'=> $this->settings->get('alipay_qr_pay_mode'));
        ] ;
    }
}
