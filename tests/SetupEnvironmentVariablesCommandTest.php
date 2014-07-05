<?php

use Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand;
use Brunty\LaravelEnvironment\Helpers\ArrayHelper;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SetupEnvironmentVariablesCommandTest extends TestCase {

    protected $command;
    protected $application;
    protected $commandTester;

    public function setUp()
    {
        parent::setUp();

        $this->application = new Application();
        $this->application->add(new SetupEnvironmentVariablesCommand(new Filesystem(), new ArrayHelper()));
        $this->command = $this->application->find('env:setup');
        $this->commandTester = new CommandTester($this->command);
        /*
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

    public function testBasic() {
      $this->commandTester->execute(array('command' => $this->command->getName())); // this will cause phpunit to hang as the command usually requires input

    }

    /*
    public function testBasicInputToRows() {
        $inputArray = ['something' => 'value'];

        $expectedResult = [
            '0' =>  [
                'something',
                'value'
            ]
        ];

        $actualResult = $this->callMethod($this->command, 'inputToRows', [$inputArray]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testAlphabeticallyOrganisedInputToRows() {
        $inputArray = [
            'something'         =>  'value',
            'another.thing'     =>  'another-value',
        ];

        $expectedResult = [
            '0' =>  [
                'another.thing',
                'another-value'
            ],
            '1' =>  [
                'something',
                'value'
            ]
        ];

        $actualResult = $this->callMethod($this->command, 'inputToRows', [$inputArray]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetKeyFilePath() {
        $expectedPath = '';
        $actualPath = $this->command->getKeyFilePath();

        $this->assertEquals($expectedPath, $actualPath);
    }

    public function testFire() {

        $this->assertTrue(true);
    }
    */
}