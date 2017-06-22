<?php
/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-14 下午9:01
 */


namespace Notadd\Multipay\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;

/*
 * Classs RefundHandler
 */
class RefundHandler extends Handler
{
    /**
     * @var \Notadd\Multipay\Multipay
     */
    protected $multipay;
    protected $config;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->multipay = $this->container->make('Multipay');
    }

    /*
     * Execute Handler
     */
    public function execute(){
        $data = $this->multipay->refund();
        $result = ['data' => $data];
        if ($data==402) {
            $this->withCode(402)->withError('缺少关键参数,请查看相关开发文档');
        }elseif($data == false){
            $this->withCode(500)->withError('退款失败，请稍候重试');
        }else{
            $this->withCode(200)->withData($result)->withMessage('退款成功');
        }
    }
}