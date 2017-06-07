<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/24
 * Time: 11:48
 */

namespace Notadd\Multipay\Controllers;
use Notadd\Multipay\Handlers\GetWechatconfHandler;
use Notadd\Multipay\Handlers\SetWechatconfHandler;
use Notadd\Multipay\Handlers\UploadHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class WechatconfController.
 */
class WechatController extends Controller
{
    /**
     * Get handler.
     *
     * @param \Notadd\Multipay\Handlers\GetHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function get(GetWechatconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * Set handler.
     *
     * @param \Notadd\MUltipay\Handlers\SetHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */
    public function set(SetWechatconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

<<<<<<< HEAD

=======
>>>>>>> e4204a8fe0e4c61d7c2c3f80104aa80ce9b16339

}
