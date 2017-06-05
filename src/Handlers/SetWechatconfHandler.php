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
            $this->settings->set('wechat.certpath',$this->request->input('certpath')),
            $this->settings->set('wechat.keypath',$this->request->input('keypath')),
            $this->settings->set('wechat.key',$this->request->input('key')),
        );

        return true ;
    }
}
