<?php

namespace \Notadd\Multipay;

use Illuminate\Contracts\Container\Container;

interface Payment extends Container
{
    public function pay();

    
}
