<?php namespace Brunty\LaravelEnvironment\Commands;

use Illuminate\Filesystem\FileNotFoundException;
use Illuminate\Support\Str as Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class SetupEnvironmentVariablesCommand
 * @package Brunty\LaravelEnvironment\Commands
 */
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


    /*
     * This array is used to hold the input as entered by the user
     *
     * @var array
     */
    protected $envVarsInput = [];

    /*
     * This array holds the actual values stored in array format as they'll be stored in the environment file
     *
     * @var array
     */
    protected $envVars = [];
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

        $envVar = $this->ask('Enter the name of the environment variable (blank to finish setup): ');

        while(trim($envVar) != '') {
            $value = $this->ask('Enter the value of the environment variable: ');
            $this->info('');

            $this->envVarsInput[$envVar] = $value;

            $envVar = $this->ask('Enter the name of the environment variable (blank to finish setup): ');
        }


        $this->info('');

        $this->envVars = $this->stringPathToArrayKey($this->envVarsInput);

        $contents = $this->getKeyFileArray();

        $this->envVarsInput = $this->mergeDownArrays($this->envVarsInput, $contents);

        $this->envVars += $contents; // merge the two arrays - our old env vars with our new ones over-writing any duplicate keys

        $rows = $this->envToRows($this->envVarsInput); // just use input for nice dot separated syntax

        $headers = ['Key', 'Value'];

        // Display a table of the values
        $this->info('Full contents:');
        $this->table($headers, $rows);
        $this->info('');

        if ($this->confirm('Are you sure you want to write these values? [yes|no]', false))
        {

            $path = $this->getKeyFilePath();

            $this->files->put($path, '<?php

return ' . var_export($this->envVars, true) . ';');

            $this->info("Application environment variables set.");
        }
        else
        {
            $this->error('Ending command, environment file not setup.');
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

    /**
     * @return string
     */
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

    /**
     * @param $envVars
     * @return array
     */
    private function envToRows($inputArray = [])
    {
        $rows = [];

        ksort($inputArray);
        foreach($inputArray as $envVar => $value) {
            $rows[] = [$envVar, $value];
        }

        return $rows;
    }

    private function mergeDownArrays($envVarsInput, $contents)
    {
        $envVarsInput += $contents; // merge two arrays

        return $envVarsInput;
    }

    private function stringPathToArrayKey($input = [])
    {
        $tempArray = [];
        foreach($input as $envVar => $value) {
            $path = explode('.', $envVar);
            $root = &$tempArray;
            while(count($path) > 1) {
                $branch = array_shift($path);
                if (!isset($root[$branch])) {
                    $root[$branch] = array();
                }

                $root = &$root[$branch];
            }

            $root[$path[0]] = $value;
        }

        return $tempArray;
    }

}