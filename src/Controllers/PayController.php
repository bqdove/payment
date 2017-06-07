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
use Illuminate\Container\Container;

class PayController extends Controller{

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var
     */
    protected $multipay;

    public function __construct()
    {
        parent::__construct();
        $this->pay = $this->container->make('pay');
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    public function pay(){
        return $this->multipay->driver()->pay();
    }
    public function execute()
    {
    }
}