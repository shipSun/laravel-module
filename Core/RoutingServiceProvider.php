<?php
namespace Core;

use Illuminate\Routing\RoutingServiceProvider as BaseRountingServiceProvider;

class RoutingServiceProvider extends BaseRountingServiceProvider{
	/**
	 * Register the router instance.
	 *
	 * @return void
	 */
	protected function registerRouter()
	{
		$this->app['router'] = $this->app->share(function ($app) {
			return new Router($app['events'], $app);
		});
	}
}