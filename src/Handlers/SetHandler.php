<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-02-18 20:33
 */
namespace Notadd\Multipay\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Passport\Abstracts\SetHandler as AbstractSetHandler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class AlipayHandler.
 */
class SetHandler extends AbstractSetHandler
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
        $this->settings->set('alipay.partner_id', $this->request->input('partner_id'));
        $this->settings->set('alipay.seller_id',$this->request->input('seller_id'));
        $this->settings->set('alipay.wkey',$this->request->input('wkey'));
        $this->settings->set('alipay.wsign_type',$this->request->input('wsign_type'));
        $this->settings->set('alipay.wnotify_url',$this->request->input('wnotify_type'));
        $this->settings->set('alipay.wreturn_url',$this->request->input('wreturn_url'));
        $this->settings->set('wechat.app_id',$this->request->input('app_id'));
        $this->settings->set('wechat.secret',$this->request->input('secret'));
        $this->settings->set('wechat.token',$this->request->inpput('token'));
        $this->settings->set('wechat.aes_key',$this->request-input('aes_key'));
        $this->settings->set('unionpay.merId',$this->request->input('merId'));
        $this->settings->set('unionpay.certPath',$this->request->input('certPath'));
        $this->settings->set('unionpay.certPassword',$this->request->input('certPassword'));
        $this->settings->set('unionpay.certDir',$this->request->input('certDir'));
        $this->settings->set('unionpay.ureturnUrl',$this->request->input('ureturnUrl'));
        $this->settings->set('unionpay.unotifyUrl',$this->request->input('unotifyUrl'));
        return true ;
    }


}
