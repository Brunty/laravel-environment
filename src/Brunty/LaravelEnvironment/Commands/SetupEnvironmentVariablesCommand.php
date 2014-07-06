<?php namespace Brunty\LaravelEnvironment\Commands;

use Brunty\LaravelEnvironment\Helpers\ArrayHelper;
use Brunty\LaravelEnvironment\Helpers\InputHelper;
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
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Brunty\LaravelEnvironment\Helpers\ArrayHelper
     */
    protected $array;

    /**
     * Create a new key generator command.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param \Brunty\LaravelEnvironment\Helpers\ArrayHelper $array
     */
    public function __construct(
        Filesystem $files,
        ArrayHelper $array
    )
    {
        parent::__construct();

        $this->files = $files;
        $this->array = $array;
    }

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        $this->envVarsInput = $this->getUserInput();

        $this->separatorLine();

        // get our existing content
        $contents = $this->getKeyFileArray();

        // turn the existing contents into an array with their keys as strings (to match the format of user input)
        $contents = $this->array->arrayKeyToStringPath($contents);

        //merging our arrays, we take the input that the user's entered and merge it with the existing contents
        $this->envVarsInput = $this->array->mergeDownArrays($this->envVarsInput, $contents);

        // Turn the input that the user has entered (along with the existing content) and convert the string keys back to proper array keys
        $this->envVars = $this->array->stringPathToArrayKey($this->envVarsInput);

        // Display a table of the values
        $this->info('Full contents:');
        $this->envTable(['Key', 'Value'], $this->array->inputToTableRows($this->envVarsInput));
        $this->separatorLine();

        // confirm and (possibly) write the file!
        $this->confirmWrite($this->getKeyFilePath(), $this->envVars);
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
     * @param $path
     * @param $envVars
     */
    public function createFile($path, $envVars)
    {
        // TODO: refactor this
        $varContent = var_export($envVars, true);
        $message = $this->getGenerationMessage();
        $fileContent = <<<CONTENT
<?php

// {$message}

return {$varContent};

CONTENT;
        $this->files->put($path, $fileContent);
    }

    /**
     * @param string $content
     * @param string $type
     */
    private function separatorLine($content = '', $type = 'info')
    {
        $this->$type($content); // output separator line to CLI (potentially update to run checks on type)
    }


    /**
     * @return array
     */
    private function getUserInput()
    {
        $userInput = [];
        $envVar = $this->ask('Enter the name of the environment variable (blank to finish setup): ');

        while(trim($envVar) != '') {
            $value = $this->ask('Enter the value of the environment variable: ');

            $this->separatorLine();

            $userInput[$envVar] = $value;

            $envVar = $this->ask('Enter the name of the environment variable (blank to finish setup): ');
        }
        return $userInput;
    }

    /**
     * @param $headers
     * @param $rows
     */
    private function envTable($headers, $rows)
    {
        $this->table($headers, $rows);
    }

    /**
     * @param $path
     * @param $vars
     */
    private function confirmWrite($path, $vars)
    {
        if ($this->confirm('Are you sure you want to write these values? [yes|no]', false))
        {
            $this->createFile($path, $vars);
            $this->info("Application environment variables set.");
        }
        else
        {
            $this->error('Ending command, environment file not setup.');
        }
    }

    /**
     * @return string
     */
    private function getGenerationMessage() {
        return 'File set @ ' . date('l jS \of F Y h:i:s A') . ' by brunty/laravel-environment generation command';
    }

}