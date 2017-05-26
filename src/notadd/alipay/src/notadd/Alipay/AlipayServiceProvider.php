<?php
namespace Notadd\Alipay;

use Notadd\Support\PayServiceProvider;

class AlipayServiceProvider extends PayServiceProvider
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

		$this->payment->bind('alipay.web', function ($payment)
		{
			$alipay = new Web\SdkPayment();

			$alipay->setPartner($payment->config->get('notadd-alipay.partner_id'))
				->setSellerId($payment->config->get('notadd-alipay.seller_id'))
				->setKey($payment->config->get('notadd-alipay-web.key'))
				->setSignType($payment->config->get('notadd-alipay-web.sign_type'))
				->setNotifyUrl($payment->config->get('notadd-alipay-web.notify_url'))
				->setReturnUrl($payment->config->get('notadd-alipay-web.return_url'))
				->setExterInvokeIp($payment->request->getClientIp());

			$this->payment

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
