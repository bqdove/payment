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
 * Class SetUnionpayconfHandler.
 */
class SetUnionpayconfHandler extends AbstractSetHandler
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
     * Execute Handler.
     *
     * @return bool
     */
    public function execute()
    {
        $this->settings->set('unionpay.enabled',  $this->request->input('enabled'));

        $this->settings->set('unionpay.mer_id',$this->request->input('mer_id'));

        $this->settings->set('unionpay.key',$this->request->input('key'));

        $this->settings->set('unionpay.version','5.0');

        $this->settings->set('unionpay.signMethod','01');

        $this->settings->set('unionpay.encoding','utf-8');

        return true;
    }
}
