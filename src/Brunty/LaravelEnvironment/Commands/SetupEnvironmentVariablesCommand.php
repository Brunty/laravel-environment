<?php namespace Brunty\LaravelEnvironment\Commands;

use Illuminate\Filesystem\FileNotFoundException;
use Illuminate\Support\Str as Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class SetupEnvironmentVariablesCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'env:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Setup the environment file(s) for a Laravel application.";

    /**
     * Create a new key generator command.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @return \Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     * Initially this just started as a way to re-generate the key in laravel using my own string library, decided to extend it.
     * @return void
     */
    public function fire()
    {
        $this->error('This command regenerates the system key for the application, any previously encrypted passwords will no longer be able to be viewed if this is done.');
        $this->info('The key is set in the environment file (.env.php) - if the various environment variables are not set on the server, this file can be used to set them.');
        $this->comment('Recommend clearing out the passwords in the DB if you regenerate the key.');
        $this->comment('Do this with "php artisan securesend:cleareverything"');

        $envVars = [];

        $envVar = $this->ask('Enter the name of the environment variable (blank to exit): ');

        while($envVar != '') {
            $value = $this->ask('Enter the value of the environment variable: ');

            $envVars[$envVar] = $value;

            $envVar = $this->ask('Enter the name of the environment variable (blank to exit): ');
        }

        if ($this->confirm('Are you sure you want to write these values? [yes|no]', false))
        {
            // now we can merge
            $contents = $this->getKeyFileArray();
            $path = $this->getKeyFilePath();

            $envVars += $contents; // merge the two arrays

            $this->files->put($path, '<?php

return ' . var_export($envVars, true) . ';');

            $this->info("Application environment variables set.");
        }
        else
        {
            $this->info('Ending command, environment file not setup.');
        }

    }

    /**
     * Get the key file and contents.
     *
     * @return array
     */
    protected function getKeyFileArray()
    {
        $path = $this->getKeyFilePath();
        $defaultEnvFile = dirname(__FILE__)."/../Files/.env.default.php";

        if( ! file_exists($path)) {
            $this->files->copy($defaultEnvFile, $path); // copy default file
        }

        $envConfig = include($path);

        return $envConfig;
    }
    public function getKeyFilePath() {

        $env = $this->option('env') ? '.' . $this->option('env') : '';
        $path = base_path()."/.env{$env}.php";
        return $path;
    }
    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function getRandomKey()
    {
        return Str::random(32);
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
        );
    }

}