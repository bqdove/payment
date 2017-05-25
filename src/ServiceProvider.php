<?php

namespace Notadd\Support;

use Illuminate\Support\ServiceProvider as ServiceProvider;
use Illuminate\Console\Application as Artisan;

class PayServiceProvider extends ServiceProvider
{
    /**
     * The pay instance.
     *
     * @var \Notadd\Multipay\
     */
     protected $payment;

}
