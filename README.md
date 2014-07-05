# Setup and work with .env files in Laravel from the command line

[![Build Status](https://travis-ci.org/Brunty/laravel-environment.png?branch=master)](https://travis-ci.org/Brunty/laravel-environment) [![Coverage Status](https://coveralls.io/repos/Brunty/laravel-environment/badge.png?branch=master)](https://coveralls.io/r/Brunty/laravel-environment?branch=master)

# TO DO:
- Inject an input helper into the command so that it can be tested without requesting user input - can just mock the input helper to return what we want.
- Write full tests for the command once the above has been completed

This Laravel 4 package gives you a way to setup and work with your environment files within Laravel from the command line.

- `env:setup`

## Installation

Install the package through Composer. Edit your project's `composer.json` file to require `brunty/laravel-environment`.

	"require-dev": {
		"brunty/laravel-environment": "0.*"
	}

Next, update Composer from the Terminal:

    composer update

Once this has completed, add the service provider to your service providers array in `app/config/app.php`

    'Brunty\LaravelEnvironment\LaravelEnvironmentServiceProvider'

Previously, you may have entered your environment variables like so:

    'key'   =>  $_ENV['ENV_VAR'],

This will cause issues with the package if you don't have an .env file for your environment (this package can create them from blank if required)

If creation is required, you should reference `$_ENV` in the following way:

    'key'   =>  isset($_ENV['ENV_VAR']) ?: null,

Where null is a default value taht suits you.

Once that's done you're ready to start using the package

You can see the commands within artisan

    php artisan

## Usage


- `php artisan env:setup`

Use the above command to setup an environment file, it works with the optional `--env` flag to specify what environment the file should be for.

When using this command, it'll prompt you for the variable name, then the value, it'll do this until you finish setup.

http://i.imgur.com/jIEaD1j.jpg