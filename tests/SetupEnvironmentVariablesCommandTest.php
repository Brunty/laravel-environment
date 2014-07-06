<?php

use Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand;
use Brunty\LaravelEnvironment\Helpers\ArrayHelper;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class SetupEnvironmentVariablesCommandTest
 */
class SetupEnvironmentVariablesCommandTest extends TestCase {

    /**
     * @var
     */
    protected $command;

    /**
     * @var
     */
    protected $application;

    /**
     * @var
     */
    protected $commandTester;

    /**
     * @var
     */
    protected $inputInterface;

    /**
     * @var
     */
    protected $outputInterface;

    /**
     * Setup our test - used to register commands with the Symfony command tester.
     */
    public function setUp()
    {
        parent::setUp();

        $this->application = new Application();
        $this->application->add(new SetupEnvironmentVariablesCommand(new Filesystem(), new ArrayHelper()));
        $this->command = $this->application->find('env:setup');
        $this->commandTester = new CommandTester($this->command);
        $this->inputInterface = $this->commandTester->getInput();
        $this->outputInterface = $this->commandTester->getOutput();

    }

    /**
     * Used to close any mocks we have
     */
    public function tearDown()
    {
        Mockery::close();
    }


    /**
     * @param $input
     * @return resource
     */
    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    /**
     * Use this method to eventually test that the command fires correctly.
     */
    public function testFire() {
        /*
        $helper = $this->command->getHelper('question');
        $inputStream = $this->getInputStream('Test\\n');

        $helper->setInputStream($inputStream);

        $this->commandTester->execute([
                'command' => $this->command->getName(),
                'env'   =>  'testing'
        ]);
        */

        $this->assertTrue(true);
    }
}