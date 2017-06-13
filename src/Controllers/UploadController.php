<?php
/**
 * Created by PhpStorm.
 * User: bc-203
 * Date: 17-6-6
 * Time: 下午5:27
 */

namespace Notadd\Payment\Controllers;

use Notadd\Multipay\Handlers\UploadHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Illuminate\Http\Request;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;
use SplFileInfo;

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

    public function execute( )
    {
        $filesystem=new Filesystem();
        $uphandler = new UploadHandler($this->container,$filesystem);
        $result = $uphandler->execute();
        //dd($this->request->all());
        if($result){
            return '证书上传成功！';
        }
    }


}