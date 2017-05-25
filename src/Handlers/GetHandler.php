<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-03-18 15:24
 */
namespace Notadd\Multipay\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Passport\Abstracts\DataHandler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class GetHandler.
 */
class GetHandler extends DataHandler
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
    public function data()
    {
        return [

            'alipay_enabled'=>$this->settings->get('alipay.alipay_enabled',false),
            'key'=>$this->settings->get('alipay.key',''),
            'wechat_enabled'=>$this->settings->get('wechat.wechat_enabled',false),
            'appid'=>$this->settings->get('wechat.appid',''),
            'mchid'=>$this->settings->get('wechat.mchid',''),
            'appsecret'=>$this->settings->get('wechat.appsecret',''),
            'wkey'=>$this->settings->get('wechat.wkey',''),
            'unionpay_enabled'=>$this->settings->get('unionpay.unionpay_enabled',false),
            'merId'=>$this->settings->get('unionpay.merId',''),
            'cerPath'=>$this->settings->get('unionpay.certPath',''),
            'certPassword'=>$this->settings->get('unionpay.certPassword',''),
            'certDir'=>$this->settings->get('unionpay.certDir',''),
        ] ;
    }
}
