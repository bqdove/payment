<?php
/**
 * Created by PhpStorm.
 * User: bc-203
 * Date: 17-6-6
 * Time: 下午5:27
 */

namespace Notadd\Multipay\Controllers;

use Notadd\Multipay\Handlers\UploadHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Illuminate\Http\Request;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;

use Illuminate\Filesystem\Filesystem;

class UploadController extends Controller
{

    /**
     * @param \Notadd\Pay\Handlers\UploadHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */


    public function upload()
    {
        return view('multipay::upload');
    }

    public function execute()
    {

        $filesystem = new Filesystem();
        $uphandler = new UploadHandler($this->container, $filesystem);
        $result = $uphandler->execute();

        if ($result){
            return 'success';
        }
    }

}