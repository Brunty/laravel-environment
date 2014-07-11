<?php

use Brunty\LaravelEnvironment\Helpers\FileSystemHelper;
use org\bovigo\vfs\vfsStream;

/**
 * Class FileSystemHelperTest
 * This test is probably not needed, but if I ever change the function in the future it could be useful
 */
class FileSystemHelperTest extends TestCase {

    /**
     * @var
     */
    protected $arrayHelper;
    /**
     * @var
     */
    protected $root;
    /**
     * @var
     */
    protected $fileSystem;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $structure = [
            '.env.php'  =>  '<?php return ["foo" => "bar"];'
        ];
        $this->root = vfsStream::setup('root', null, $structure);

        $this->fileSystem = new FileSystemHelper();

    }

    /**
     *
     */
    public function testIncludeFile()
    {
        $this->assertFalse($this->root->hasChild('id'));
        $expectedArray = [
            'foo'   =>  'bar'
        ];
        $includedArray = $this->fileSystem->includeFile(vfsStream::url('root').'/.env.php');
        $this->assertEquals($expectedArray, $includedArray);
    }

}