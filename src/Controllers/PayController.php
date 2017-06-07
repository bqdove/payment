<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/6/5
 * Time: 10:54
 */

namespace Notadd\Multipay\Controllers;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Multipay\Multipay;
use Illuminate\Container\Container;

class PayController extends Controller{

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var \Notadd\Multipay\Multipay
     */
    protected $multipay;

    public function __construct()
    {
        parent::__construct();
        $this->multipay = $this->container->make('multipay');
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    public function pay($driver, $way, $para)
    {
        $this->multipay->pay($driver, $way, $para);
    }

    public function query($driver, $way, $para)
    {
        $this->multipay->query($driver, $way, $para);
    }

    public function refund($driver, $way, $para)
    {
        $this->multipay->refund($driver, $way, $para);
    }

    public function cancel($driver, $way, $para)
    {
        $this->multipay->cancel($driver, $way, $para);
    }

    public function webNotice($driver, $way, $para)
    {
        $this->multipay->webNotice($driver, $way, $para);
    }
}