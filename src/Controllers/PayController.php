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
use Notadd\Multipay\Handlers\CancelHandler;
use Notadd\Multipay\Handlers\PayHandler;
use Notadd\Multipay\Handlers\QueryHandler;
use Notadd\Multipay\Handlers\RefundHandler;
use Notadd\Multipay\Handlers\WebNotifyHandler;
use Notadd\Multipay\Models\Order;

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
        $this->multipay = $this->container->make('Multipay');
        $this->settings = Container::getInstance()->make(SettingsRepository::class);
    }

    public function pay(PayHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function query(QueryHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function refund(RefundHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function cancel(CancelHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function webNotify(WebNotifyHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();

    }


}