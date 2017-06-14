<?php
/**
 * Created by PhpStorm.
 * User: bc-203
 * Date: 17-6-14
 * Time: 下午1:56
 */

namespace Notadd\Multipay\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/*
 * Classs PayHandler
 */
class PayHandler extends Handler
{
    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var \Notadd\Multipay\Multipay
     */
    protected $multipay;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->multipay = $this->container->make('Multipay');
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    /*
     * Execute Handler
     */
    public function execute(){
        $driver = $this->request->query('driver');
        $way = $this->request->query('way');
        $para = $this->request->except(['driver','way']);
        $this->multipay->pay($driver,$way,$para);
    }

}