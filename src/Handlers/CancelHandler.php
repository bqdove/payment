<?php
/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-14 下午9:08
 */


namespace Notadd\Multipay\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;

/*
 * Classs CancelHandler
 */
class CancelHandler extends Handler
{
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
    public function execute(){
        $driver = $this->request->query('driver');
        $way = $this->request->query('way');
        $para = $this->request->except(['driver','way']);
        $this->multipay->cancel($driver,$way,$para);
    }

}