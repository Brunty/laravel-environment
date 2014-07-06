<?php

use Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand;
use Brunty\LaravelEnvironment\Helpers\ArrayHelper;
use Brunty\LaravelEnvironment\Helpers\FileSystemHelper;
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

    /*
     *
     */
    protected $root;

    /**
     * Setup our test - used to register commands with the Symfony command tester.
     */
    public function setUp()
    {
        parent::setUp();

        $this->application = new Application();
        $this->application->add(new SetupEnvironmentVariablesCommand(new FileSystemHelper(), new ArrayHelper()));
        $this->command = $this->application->find('env:setup');
        $this->commandTester = new CommandTester($this->command);
        $this->inputInterface = $this->commandTester->getInput();
        $this->outputInterface = $this->commandTester->getOutput();

        $this->fileSystem = Mockery::mock('Brunty\LaravelEnvironment\Helpers\FileSystemHelper[copy, exists, put, includeFile]');

        $this->arrayHelper = Mockery::mock('Brunty\LaravelEnvironment\Helpers\ArrayHelper[arrayKeyToStringPath, stringPathToArrayKey, mergeDownArrays]');


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
    public function testUserAsks() {
        $nameMessage = '';
        $valueMessage = '';

        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[ask]", [$this->fileSystem, $this->arrayHelper]);

        $command->shouldReceive('ask')
            ->times(1)
            ->andReturn('foo');

        $command->askInitialName($nameMessage);

        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[ask]", [$this->fileSystem, $this->arrayHelper]);

        $command->shouldReceive('ask')
            ->times(1)
            ->andReturn('bar');

        $command->askValue($valueMessage);

        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[ask]", [$this->fileSystem, $this->arrayHelper]);

        $command->shouldReceive('ask')
            ->times(1)
            ->andReturn('');

        $command->askRepeatName($nameMessage);

    }
    public function testGetUserInput() {

        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[askInitialName, askValue, separatorLine, askRepeatName]", [$this->fileSystem, $this->arrayHelper]);

        $command->shouldReceive('askInitialName')
            ->once()
            ->andReturn('foo');

        $command->shouldReceive('askValue')
            ->once()
            ->andReturn('bar');

        $command->shouldReceive('askRepeatName')
            ->once()
            ->andReturn('');

        $command->shouldReceive('separatorLine')
            ->once()
            ->andReturn('');

        $expectedInput = [
            'foo'  =>  'bar'
        ];

        $actualInput = $command->getUserInput();

        $this->assertEquals($expectedInput, $actualInput);

    }

    public function testCreateFile() {
        $message = 'Created';
        $envVars = [
            'foo'   =>  'bar'
        ];

        $varContent = var_export($envVars, true);

        $expectedFileContent = <<<CONTENT
<?php

// {$message}

return {$varContent};

CONTENT;

        $this->fileSystem->shouldReceive('put')
            ->once()
            ->andReturn($expectedFileContent);

        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[getKeyFilePath, option]", [$this->fileSystem, $this->arrayHelper]);

        $actualFileContent = $command->createFile('.env.php', $envVars);

        $this->assertEquals($expectedFileContent, $actualFileContent);

    }
    public function testSeparatorLine() {

        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[info]", [$this->fileSystem, $this->arrayHelper]);

        $command->shouldReceive('info')
            ->once()
            ->andReturn(true);

        $command->separatorLine('*****', 'info');
    }

    public function testGetKeyFileArray() {

        $this->fileSystem->shouldReceive('copy')
            ->once()
            ->andReturn(true);

        $this->fileSystem->shouldReceive('exists')
            ->once()
            ->andReturn(false);

        $envArray = [
            'test'  =>  'var'
        ];

        $this->fileSystem->shouldReceive('includeFile')
            ->once()
            ->with('.env.php')
            ->andReturn($envArray);

        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[getKeyFilePath, option]", [$this->fileSystem, $this->arrayHelper]);

        $command->shouldReceive('getKeyFilePath')
            ->once()
            ->andReturn('.env.php');

        $array = $command->getKeyFileArray();

        $this->assertEquals($array, $envArray);
    }

    /*
     * No idea what purpose this test serves... code coverage!
     */
    public function testEnvTable() {

        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[table]", [$this->fileSystem, $this->arrayHelper]);

        $headers = [];
        $rows = [];

        $command->shouldReceive('table')
            ->once()
            ->with($headers, $rows)
            ->andReturn('');

        $message = $command->envTable($headers, $rows);

        $this->assertEquals(null, $message);

    }
    public function testGetGenerationMessage() {
        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[]", [$this->fileSystem, $this->arrayHelper]);

        $message = $command->getGenerationMessage();

        $this->assertNotEmpty($message);

    }
    /*
     *
     */
    public function testGetKeyFilePath() {
        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[option]", [$this->fileSystem, $this->arrayHelper]);

        $command->shouldReceive('option')
            ->once()
            ->andReturn('');

        $filePath = $command->getKeyFilePath();

        $expectedEnvFile = '.env.php';

        $this->assertRegExp('/'.$expectedEnvFile.'/', $filePath);
    }

    public function testEnvironmentSetGetKeyFilePath() {
        $command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[option]", [$this->fileSystem, $this->arrayHelper]);

        $command->shouldReceive('option')
            ->twice()
            ->andReturn('local');

        $filePath = $command->getKeyFilePath();

        $expectedEnvFile = '.env.local.php';

        $this->assertRegExp('/'.$expectedEnvFile.'/', $filePath);
    }

    /**
     * Use this method to eventually test that the command fires correctly.
     */
    /*
    public function testFire() {

        $this->arrayHelper->shouldReceive('arrayKeyToStringPath')
            ->once()
            ->andReturn([
                'test'  =>  'value'
            ]);

        $this->arrayHelper->shouldReceive('stringPathToArrayKey')
            ->once()
            ->andReturn([
            ]);

        $this->arrayHelper->shouldReceive('mergeDownArrays')
            ->once()
            ->andReturn([
            ]);

        // given
        //
        //$command = Mockery::mock("Brunty\LaravelEnvironment\Commands\SetupEnvironmentVariablesCommand[getUserInput, separatorLine, getKeyFileArray, getKeyFilePath, info, envTable, confirmWrite, getOption]", [$fileSystem, $arrayHelper]);

        /*
        $command->shouldReceive("getUserInput")
            ->once()
            ->andReturn([
            ]);

        $command->shouldReceive("separatorLine")
            ->twice()
            ->andReturn('');

        $command->shouldReceive('getKeyFilePath')
            ->once()
            ->andReturn('');

        $command->shouldReceive('getKeyFileArray')
            ->once()
            ->andReturn([
            ]);

        $command->shouldReceive('info')
            ->once()
            ->andReturn('');

        $command->shouldReceive('envTable')
            ->once()
            ->andReturn('');

        $command->shouldReceive('confirmWrite')
            ->once()
            ->with('')
            ->with([

            ]);

        $command->shouldReceive('getOption')
            ->once()
            ->andReturn('');

        $command->shouldReceive('confirm')
            ->once()
            ->andReturn(true);

        // when
        //
        $command->getKeyPathFile();


        $this->assertTrue(true);
    }
    */
}