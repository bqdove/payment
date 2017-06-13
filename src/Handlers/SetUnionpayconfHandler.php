<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/25
 * Time: 19:35
 */

namespace Notadd\Payment\Handlers;

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

            $this->settings->set('unionpay.enabled',  $this->request->input('union_enabled')),
            $this->settings->set('unionpay.unionpay_enabled', $this->request->input('unionpay_enabled')),
            $this->settings->set('unionpay.driver', $this->request->input('driver')),
            $this->settings->set('unionpay.merId',$this->request->input('merId')),
            $this->settings->set('unionpay.certPath',$this->request->input('certPath')),
            $this->settings->set('unionpay.certPassword',$this->request->input('certPassword')),
            $this->settings->set('unionpay.certDir',$this->request->input('cerDir')),
            $this->settings->set('unionpay.returnUrl',$this->request->input('returnUrl')),
            $this->settings->set('unionpay.notifyUrl',$this->request->input('notifyUrl')),
        );

        return true ;
    }
}
