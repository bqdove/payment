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

        //微信二维码
        if ($data['type'] == 'wechat')
        {
            $result = ['data' => $data['base64']];
            $this->container->make('files')->delete($data['qrcode']);
            $this->withCode(200)->withData($result)->withMessage('获取base64编码的支付二维码成功');
        }elseif($data['type'] == 'alipay'){
            $result = ['data' => $data['url']];
            $this->withCode(200)->withData($result)->withMessage('获取支付跳转链接成功');
        }
    }

}