<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/27
 * Time: 19:30
 */

namespace Notadd\Multipay\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Passport\Abstracts\DataHandler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class GetUnionpayconfHandler.
 */
class GetUnionpayconfHandler extends DataHandler
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
            'unionpay.enabled'=>$this->settings->get('unionpay.enabled', false),
            'merId'=>$this->settings->get('unionpay.merId',''),
            'key'=>$this->settings->get('unionpay.key',''),
            'certPath'=>$this->settings->get('unionpay.certPath',''),
            'certPassword'=>$this->settings->get('unionpay.certPassword',''),
            'certDir'=>$this->settings->get('unionpay.certDir',''),
            'version'=>$this->settings->get('union.version',''),
            'signMethod'=>$this->settings->get('unionpay.signMethod',''),
            'encoding'=>$this->settings->get('unionpay.encoding',''),
        ] ;
    }

    public function execute()
    {
        $this->data();
    }
}