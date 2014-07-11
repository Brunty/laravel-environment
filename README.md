# Setup and work with .env files in Laravel from the command line

[![Latest Stable Version](https://poser.pugx.org/brunty/laravel-environment/v/stable.svg)](https://packagist.org/packages/brunty/laravel-environment) [![Build Status](https://travis-ci.org/Brunty/laravel-environment.png?branch=master)](https://travis-ci.org/Brunty/laravel-environment) [![Coverage Status](https://coveralls.io/repos/Brunty/laravel-environment/badge.png?branch=master)](https://coveralls.io/r/Brunty/laravel-environment?branch=master) [![License](https://poser.pugx.org/brunty/laravel-environment/license.svg)](https://packagist.org/packages/brunty/laravel-environment)

## Future features
- Allow for more config types for values - currently only strings are supported, I want to add support for constants, integers, booleans etc.

This Laravel 4 package gives you a way to setup and work with your environment files within Laravel from the command line.

- `env:configure`

## Installation

Install the package through Composer. Edit your project's `composer.json` file to require `brunty/laravel-environment`.

	"require": {
		"brunty/laravel-environment": "0.*"
	}

Next, update Composer from the Terminal:

    composer update

Once this has completed, add the service provider to your service providers array in `app/config/app.php`

    'Brunty\LaravelEnvironment\LaravelEnvironmentServiceProvider'

You should then be able to see the command within artisan

    php artisan

## Usage

- `php artisan env:configure`

Use the above command to setup and/or configure an environment file, it works with the optional `--env` flag to specify what environment the file should be for.

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

## Access environment variables

Previously, you _may_ have accessed your environment variables with the `$_ENV` superglobal like so:

    'key'   =>  $_ENV['ENV_VAR'],

This can cause undefined index errors if you don't have a file for your environment already setup (this package can create a file from blank if required) when using this way of accessing environment variables.

I would recommend that you reference your environment variables using `getenv('varname')` which will simply return fales if the environment variable doesn't exist.

## Notes:
- Using this command, you can over-write previous values, to do this, just give the same name as the existing value, and it'll over-write the old values as it merges the user input with any existing values.
- If using multi-dimensional arrays, you cannot specify both a value for an item, and have an array in that same item.
- This command assumes you're running it under a user who has permission to write files if needed (as well as create the file if it doesn't already exist)


### Example of an early version working

![Example of an early version working](http://i.imgur.com/jIEaD1j.jpg)