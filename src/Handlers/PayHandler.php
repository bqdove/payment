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

/*
 * Classs PayHandler
 */
class PayHandler extends Handler
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
        $data = $this->multipay->pay();
        $result = ['data' => $data];
        if ($data) {
            $this->withCode(200)->withData($result)->withMessage('获取支付跳转链接成功');
        } else {
            $this->withCode(500)->withError('获取支付跳转链接失败');
        }
    }

}