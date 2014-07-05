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
}