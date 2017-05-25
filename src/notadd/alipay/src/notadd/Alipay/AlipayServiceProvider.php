<?php
namespace Notadd\Alipay;

use Notadd\Support\ServiceProvider;

class AlipayServiceProvider extends ServiceProvider
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

		$this->alipay->bind('alipay.web', function ($alipay)
		{
			$aliPayment = new Web\SdkPayment();

			$aliPayment->setPartner($alipay->config->get('notadd-alipay.partner_id'))
				->setSellerId($alipay->config->get('notadd-alipay.seller_id'))
				->setKey($alipay->config->get('notadd-alipay-web.key'))
				->setSignType($alipay->config->get('notadd-alipay-web.sign_type'))
				->setNotifyUrl($alipay->config->get('notadd-alipay-web.notify_url'))
				->setReturnUrl($alipay->config->get('notadd-alipay-web.return_url'))
				->setExterInvokeIp($alipay->request->getClientIp());

			return $aliPayment;
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
