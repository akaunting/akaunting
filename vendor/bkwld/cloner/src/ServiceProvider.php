<?php namespace Bkwld\Cloner;

// Deps
use Bkwld\Cloner\Adapters\Upchuck as UpchuckAdapter;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Bootstrap the package for Laravel
 */
class ServiceProvider extends LaravelServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {

		// Instantiate main Cloner instance
		$this->app->singleton('cloner', function($app) {
			return new Cloner(
				$app['cloner.attachment-adapter'],
				$app['events']
			);
		});

		// Instantiate default Upchuck attachment adapter if the app is using Upchuck.
		$this->app->singleton('cloner.attachment-adapter', function($app) {
			if (empty($app['upchuck'])) return;
			return new UpchuckAdapter(
				$app['upchuck'],
				$app['upchuck.storage'],
				$app['upchuck.disk']
			);
		});

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return [
			'cloner',
			'cloner.attachment-adapter',
		];
	}

}
