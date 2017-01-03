<?php
namespace Core;

use Illuminate\Foundation\Application as BaseApplication;
use Illuminate\Events\EventServiceProvider;

class Application extends BaseApplication{
	/**
	 * Register all of the base service providers.
	 *
	 * @return void
	 */
	protected function registerBaseServiceProviders()
	{
		$this->register(new EventServiceProvider($this));
	
		$this->register(new RoutingServiceProvider($this));
	}
}