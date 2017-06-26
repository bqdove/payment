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

class UploadController extends Controller
{

    /**
     * @param \Notadd\Multipay\Handlers\UploadHandler $handler
     *
     */
    public function test()
    {
        return view('multipay::upload');
    }

    public function execute(UploadHandler $handler, Request $request)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}