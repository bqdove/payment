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
            'wechat_enabled'=>$this->settings->get('wechat.enabled',false),

            'app_id'=>$this->settings->get('wechat.app_id',''),

            'mch_id'=>$this->settings->get('wechat.mch_id',''),

            'app_secret'=>$this->settings->get('wechat.app_secret',''),

            'key'=>$this->settings->get('wechat.key',''),

            'cert_path'=>$this->settings->get('wechat.cert_path','')
        ];
    }
    public function execute()
    {
        $this->data();
    }
}