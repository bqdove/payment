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


    public function upload(){
        return view('upload::upload');
    }

    public function uploadcert(Request $request){

        $this->validate($request, [
            'cert' => 'required',
        ],[
            'cert'=>'文件上传不能为空'
        ]);
        $path ='../storage/cert';

        $filename =$_FILES['cert']['name'];

        $request->file('cert')->move($path,$filename);
        dd(pathinfo($_FILES['cert']['name']));

    }

}