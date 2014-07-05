<?php namespace Brunty\LaravelEnvironment;

use Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand;
use Brunty\LaravelEnvironment\Helpers\ArrayHelper;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class LaravelEnvironmentServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('Brunty/LaravelEnvironment');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
         * We have 4 types of commands
         * Setup (copies a blank file and prompts for keys and values)
         * Edit (allows you to enter a key to edit the value for)
         * Add (allows you to add a new key and value to an existing file)
         * Remove (Generally not recommended)
         */

        // Register our Commands
        $this->app['setup.env'] = $this->app->share(function($app)
        {
            return new SetupEnvironmentVariablesCommand(new Filesystem(), new ArrayHelper());
        });

        $this->commands('setup.env');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}