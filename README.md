# Setup and work with .env files in Laravel from the command line

[![Latest Stable Version](https://poser.pugx.org/brunty/laravel-environment/v/stable.svg)](https://packagist.org/packages/brunty/laravel-environment) [![Build Status](https://travis-ci.org/Brunty/laravel-environment.png?branch=master)](https://travis-ci.org/Brunty/laravel-environment) [![Coverage Status](https://coveralls.io/repos/Brunty/laravel-environment/badge.png?branch=master)](https://coveralls.io/r/Brunty/laravel-environment?branch=master) [![License](https://poser.pugx.org/brunty/laravel-environment/license.svg)](https://packagist.org/packages/brunty/laravel-environment)

# TO DO:
- Work out how the hell to properly test a command that requires user interaction...
- Write full tests for the command once the above has been completed

This Laravel 4 package gives you a way to setup and work with your environment files within Laravel from the command line.

- `env:setup`

## Installation

Install the package through Composer. Edit your project's `composer.json` file to require `brunty/laravel-environment`.

	"require": {
		"brunty/laravel-environment": "0.*"
	}

Next, update Composer from the Terminal:

    composer update

Once this has completed, add the service provider to your service providers array in `app/config/app.php`

    'Brunty\LaravelEnvironment\LaravelEnvironmentServiceProvider'

Previously, you may have entered your environment variables like so:

    'key'   =>  $_ENV['ENV_VAR'],

This will cause issues with the package if you don't have an .env file for your environment (this package can create them from blank if required) when using this way of referencing environment variables.

If creation is required, you should reference your environment variables using `getenv('varname')` - alternative you _could_ use:

    'key'   =>  isset($_ENV['ENV_VAR']) ?: null,

Where null is a default value that suits you.

Once that's done you're ready to start using the package

You can see the commands within artisan

    php artisan

## Usage


- `php artisan env:setup`

Use the above command to setup an environment file, it works with the optional `--env` flag to specify what environment the file should be for.

When using this command, it'll prompt you for the variable name first, then the value.

If you want to specify a multi-dimensional array of items, you can use dot notation:

    db.host

With a value of `foo`

Would be put into the .env file under:

    [
        'db'    =>  [
            'host'  =>  'foo'
        ]
    ]

To finish setup, just hit enter without giving a name when the command prompts you for a name.

It'll then give you a table showing the values that will be written to the file and prompt you to confirm that you want to write these values.

## Notes:
- Using this command, you can over-write previous values, to do this, just give the same name as the existing value, and it'll over-write the old values as it merges the user input with any existing values.
- If using multi-dimensional arrays, you cannot specify both a value for an item, and have an array in that same item.


![Example of it working](http://i.imgur.com/jIEaD1j.jpg)