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
            $config=array(
                'wechat_enabled'=>$this->settings->get('wechat.wechat_enabled',false),
                'app_id'=>$this->settings->get('wechat.app_id',''),
                'mch_id'=>$this->settings->get('wechat.mch_id',''),
                'appsecret'=>$this->settings->get('wechat.appsecret',''),
                'key'=>$this->settings->get('wechat.key',''),
                'refund'=>$this->settings->get('wechat.refund',''),
                'path'=>$this->settings->get('wechat.path',''),
                'out_trade_no'=>$this->settings->get('wechat.out_trade_no',''),
                'total_fee'=>$this->settings->get('wechat.total_fee',''),
                'trade_type'=>$this->settings->get('wechat.trade_type',''),
                'notify_url'=>$this->settings->get('wechat.notify_url',''),
                'openid'=>$this->settings->get('wechat.openid',''),
                'product_id'=>$this->settings->get('wechat.product_id',''),
            )
        ] ;
    }
}