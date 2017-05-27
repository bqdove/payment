<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/25
 * Time: 19:33
 */

namespace Notadd\Multipay\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Passport\Abstracts\DataHandler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class GetWechatconfHandler.
 */
class GetWechatconfHandler extends DataHandler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * GetWechatconfHandler constructor.
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
            $config['wechat_enabled']=>$this->settings->get('wechat.wechat_enabled',false),
            $config['appid']=>$this->settings->get('wechat.appid',''),
            $config['mch_id']=>$this->settings->get('wechat.mch_id',''),
            $config['appsecret']=>$this->settings->get('wechat.appsecret',''),
            $config['wkey']=>$this->settings->get('wechat.wkey',''),
            $config['body']=>$this->settings->get('wachat.body',''),
            $config['out_trade_no']=>$this->settings->get('wechat.out_trade_no',''),
            $config['total_fee']=>$this->settings->get('wechat.total_fee',''),
            $config['trade_type']=>$this->settings->get('wechat.trade_type',''),
            $config['openid']=>$this->settings->get('wechat.openid',''),
            $config['product_id']=>$this->settings->get('wechat.product_id','')
        ] ;
    }
}