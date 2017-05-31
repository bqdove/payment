<?php
namespace Notadd\Multipay\Union;

use Notadd\Support\PayServiceProvider;


class UnionServiceProvider extends PayServiceProvider
{
	/**
	 * boot process
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/../../config/config.php' => config_path('notadd-alipay.php'),
			__DIR__ . '/../../config/web.php' => config_path('notadd-alipay-web.php')
		]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'notadd-alipay');
		$this->mergeConfigFrom(__DIR__ . '/../../config/web.php', 'notadd-alipay-web');

		$this->app->bind('alipay.web', function ($app)
		{
			$alipay = new Web\Sdkapp();

			$alipay->setPartner($app->config->get('notadd-alipay.partner_id'))
				->setSellerId($app->config->get('notadd-alipay.seller_id'))
				->setKey($app->config->get('notadd-alipay-web.key'))
				->setSignType($app->config->get('notadd-alipay-web.sign_type'))
				->setNotifyUrl($app->config->get('notadd-alipay-web.notify_url'))
				->setReturnUrl($app->config->get('notadd-alipay-web.return_url'))
				->setExterInvokeIp($app->request->getClientIp());

			return $alipay;
		});

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'alipay.web',
		];
	}
}
