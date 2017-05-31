<?php

namespace Notadd\Support;

use Illuminate\Support\ServiceProvider as ServiceProvider;
use Illuminate\Console\Application as Artisan;

class PayServiceProvider extends ServiceProvider
{
    /**
     * The pay instance.
     *
     * @var
     */
     protected $app;

     /**
      * Indicates if loading of the provider is deferred.
      *
      * @var bool
      */
     protected $defer = false;

     /**
      * The paths that should be published.
      *
      * @var array
      */
     protected static $publishes = [];

     /**
      * The paths that should be published by group.
      *
      * @var array
      */
     protected static $publishGroups = [];

     /**
      * Create a new service provider instance.
      *
      * @param  \Notadd\Multipay\Payment  $payment
      * @return void
      */
     public function __construct($app)
     {
         $this->app = $app;
     }

     public function register()
     {
        
     }

     public function boot()
     {

     }
}
