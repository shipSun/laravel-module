<?php
/**
 * @author ship
 */
namespace App\Providers;

use Illuminate\Routing\Redirector;
use Illuminate\Support\ServiceProvider;
use App\Http\Requests\Request;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class FoundationServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->configureFormRequests();
	}

	/**
	 * Configure the form request related services.
	 *
	 * @return void
	 */
	protected function configureFormRequests()
	{
		$this->app->afterResolving(function (ValidatesWhenResolved $resolved) {
			$resolved->validate();
		});
		$this->app->resolving(function (Request $request, $app) {
			$this->initializeRequest($request, $app['request']);
			$request->setContainer($app)->setRedirector($app->make(Redirector::class));
		});
	}

	/**
	 * Initialize the form request with data from the given request.
	 *
	 * @param  \App\Http\Requests\Request  $form
	 * @param  \Symfony\Component\HttpFoundation\Request  $current
	 * @return void
	 */
	protected function initializeRequest(Request $form, BaseRequest $current)
	{
		$files = $current->files->all();

		$files = is_array($files) ? array_filter($files) : $files;

		$form->initialize(
				$current->query->all(), $current->request->all(), $current->attributes->all(),
				$current->cookies->all(), $files, $current->server->all(), $current->getContent()
		);

		if ($session = $current->getSession()) {
			$form->setSession($session);
		}

		$form->setUserResolver($current->getUserResolver());

		$form->setRouteResolver($current->getRouteResolver());
	}
}
