<?php
/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-14 下午9:16
 */


namespace Notadd\Multipay\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Multipay\Alipay;

/*
 * Classs WebNotifyHandler
 */
class AlipayWebNotifyHandler extends Handler
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
    public function execute()
    {
        $alipay = new Alipay();
        $alipay->webNotify();
//        $this->multipay->webNotify();
    }

}