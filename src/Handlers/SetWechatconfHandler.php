<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/25
 * Time: 19:35
 */

namespace Notadd\Multipay\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Passport\Abstracts\SetHandler as AbstractSetHandler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class SetWechatconfHandler.
 */
class SetWechatconfHandler extends AbstractSetHandler
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
        $config=array(
            $this->settings->set('wechat.wechat_enabled', $this->request->input('wechat_enabled')),
            $this->settings->set('wechat.app_id',$this->request->input('app_id')),
            $this->settings->set('wechat.mch_id',$this->request->input('mch_id')),
            $this->settings->set('wechat.nonce_str',$this->request->input('nonce_str')),
            $this->settings->set('wechat.appsecret',$this->request->input('appsecret')),
            $this->settings->set('wechat.sign',$this->request->input('sign')),
            $this->settings->set('wechat.path',$this->request->input('path')),
            $this->settings->set('wechat.body',$this->request->input('body')),
            $this->settings->set('wechat.refund',$this->request->input('refund')),
            $this->settings->set('wechat.wkey',$this->request->input('wkey')),
            $this->settings->set('wechat.body',$this->request->input('body')),
            $this->settings->set('wechat.out_trade_no',$this->request->input('out_trade_no')),
            $this->settings->set('wechat.total_fee',$this->request->input('total_fee')),
            $this->settings->set('wechat.trade_type',$this->request->input('trade_type')),
            $this->settings->set('wechat.notify_url',$this->request->input('notify_url')),
            $this->settings->set('wechat.openid',$this->request->input('openid')),
            $this->settings->set('wechat.product_id',$this->request->input('product_id')),
        );

        return true ;
    }
}
