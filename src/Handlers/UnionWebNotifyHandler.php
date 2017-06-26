<?php
/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-20 下午7:51
 */


namespace Notadd\Multipay\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Multipay\Unionpay;

/*
 * Classs UnionWebNotifyHandler
 */

class UnionWebNotifyHandler extends Handler
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
    }

    /*
     * Execute Handler
     */
    public function execute()
    {
        $union = new Unionpay();
        $union->webnotify();
    }

}