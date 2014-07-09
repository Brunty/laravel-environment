<?php

use Brunty\LaravelEnvironment\Helpers\ArrayHelper;

class ArrayHelperTest extends TestCase {

    protected $arrayHelper;

    public function setUp()
    {
        parent::setUp();
        $this->arrayHelper = new ArrayHelper();

    }

    public function testBasicArrayKeyToStringPath() {
        // test basic array
        $startArray = [
            'foo'   =>  'bar'
        ];

        $expectedArray = ['foo'   =>  'bar'];
        $resultArray = $this->arrayHelper->arrayKeyToStringPath($startArray);
        $this->assertEquals($expectedArray, $resultArray);
    }

    public function testMultiDimensionalArrayKeyTOStringPath() {

        $startArray = [
            'foo'   =>  [
                'bar'   =>  'value'
            ]
        ];

        $expectedArray = ['foo.bar'   =>  'value'];
        $resultArray = $this->arrayHelper->arrayKeyToStringPath($startArray);
        $this->assertEquals($expectedArray, $resultArray);
    }

    public function testCombinationArrayKeyToStringPath() {

        $startArray = [
            'foo'   =>  [
                'bar'       =>  'value',
                'another'   =>  'moreValue',
                'second-level'   =>  [
                    'third-level'       =>  'yiss'
                ]
            ],
            'single'            =>  'entry',
            'another-single'    =>  'another-entry'
        ];

        $expectedArray = [
            'foo.bar'                       =>  'value',
            'foo.another'                   =>  'moreValue',
            'foo.second-level.third-level'  =>  'yiss',
            'single'                        =>  'entry',
            'another-single'                =>  'another-entry'
        ];

        $resultArray = $this->arrayHelper->arrayKeyToStringPath($startArray);
        $this->assertEquals($expectedArray, $resultArray);
    }

    public function testStringPathToArrayKey() {
        $expectedArray = [
            'foo'   =>  [
                'bar'   =>  'value'
            ]
        ];

        $startArray = ['foo.bar'   =>  'value'];

        $resultArray = $this->arrayHelper->stringPathToArrayKey($startArray);

        $this->assertEquals($expectedArray, $resultArray);
    }

    public function testBasicInputToRows() {
        $inputArray = ['something' => 'value'];

        $expectedResult = [
            '0' =>  [
                'something',
                'value'
            ]
        ];

        $actualResult = $this->arrayHelper->arrayToSymfonyConsoleTableRows($inputArray);

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

        $actualResult = $this->arrayHelper->arrayToSymfonyConsoleTableRows($inputArray);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testBasicMergeDownArrays() {
        $inputArray1 = [
            'foo'  =>  'bar'
        ];

        $inputArray2 = [
            'foo'   =>  'baz'
        ];

        $expectedResult = [
            'foo'   =>  'bar'
        ];

        $actualResult = $this->arrayHelper->mergeDownArrays($inputArray1, $inputArray2);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testComplexMergeDownArrays() {
        $inputArray1 = [
            'foo'  =>  'bar',
            'test.somthing' =>  'value',
            'level-1'   =>  [
                'level-2'   =>  'level-2-value'
            ]
        ];

        $inputArray2 = [
            'foo'  =>  'baz',
            'test.somthing' =>  'value',
            'level-1'   =>  [
                'level-2'   =>  'level-2-value'
            ]
        ];

        $expectedResult = [
            'foo'  =>  'bar',
            'test.somthing' =>  'value',
            'level-1'   =>  [
                'level-2'   =>  'level-2-value'
            ]
        ];

        $actualResult = $this->arrayHelper->mergeDownArrays($inputArray1, $inputArray2);

        $this->assertEquals($expectedResult, $actualResult);
    }
}