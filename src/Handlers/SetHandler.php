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
    public function __construct(Container $container, SettingsRepository $settings) {
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
<<<<<<< HEAD
        $this->settings->set('alipay.enabled', $this->request->input('alipay_enabled'));
        $this->settings->set('alipay.partner_id', $this->request->input('partner_id'));
=======

        $this->settings->set('alipay.alipay_enabled',$this->request->input('alipay_enbaled'));
>>>>>>> 0a43e4c8bfde4e6b9b2b285c2c2f437717c3bc39
        $this->settings->set('alipay.key',$this->request->input('key'));

        $this->settings->set('wechat.wechat_enabled', $this->request->input('wechat_enabled'));
        $this->settings->set('wechat.app_id',$this->request->input('app_id'));
        $this->settings->set('wechat.secret',$this->request->input('secret'));
        $this->settings->set('wechat.token',$this->request->input('token'));
        $this->settings->set('wechat.aes_key',$this->request->input('aes_key'));

        $this->settings->set('unionpay.unionpay_enabled', $this->request->input('unionpay_enabled'));
        $this->settings->set('unionpay.merId',$this->request->input('merId'));
        $this->settings->set('unionpay.certPath',$this->request->input('certPath'));
        $this->settings->set('unionpay.certPassword',$this->request->input('certPassword'));
        return true ;
    }


}
