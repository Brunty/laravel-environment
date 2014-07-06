<?php

use Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand;
use Brunty\LaravelEnvironment\Helpers\ArrayHelper;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Tester\CommandTester;

class SetupEnvironmentVariablesCommandTest extends TestCase {

    protected $command;
    protected $application;
    protected $commandTester;
    protected $inputInterface;
    protected $outputInterface;

    public function setUp()
    {
        parent::setUp();

        $this->application = new Application();
        $this->application->add(new SetupEnvironmentVariablesCommand(new Filesystem(), new ArrayHelper()));
        $this->command = $this->application->find('env:setup');
        $this->commandTester = new CommandTester($this->command);
        $this->inputInterface = $this->commandTester->getInput();
        $this->outputInterface = $this->commandTester->getOutput();

        /*
         * http://laravel.io/forum/05-14-2014-testing-artisan-commands
         * http://marekkalnik.tumblr.com/post/32601882836/symfony2-testing-interactive-console-command
         * http://symfony.com/doc/current/components/console/introduction.html
         *
         * Going to inject an object into the command that handles input - can then mock that when testing, needs some more thought though.
         */

    }

    public function tearDown()
    {
        Mockery::close();
    }


    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    /*
    public function testCommandFires() {

        $helper = $this->command->getHelper('question');
        $inputStream = $this->getInputStream('Test\\n');

        $helper->setInputStream($inputStream);

        $this->commandTester->execute([
                'command' => $this->command->getName(),
                'env'   =>  'testing'
        ]);
    }
    */


    public function testFire() {

        $this->assertTrue(true);
    }
}