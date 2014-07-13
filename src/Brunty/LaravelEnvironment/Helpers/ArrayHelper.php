<?php
namespace Brunty\LaravelEnvironment\Helpers;

/**
 * Class ArrayHelper
 * @package Brunty\LaravelEnvironment\Helpers
 */
class ArrayHelper
{
    /**
     *
     */
    const SEPARATOR = '.';

    /**
     * Converts a multi-dimensional array to dot-notation single dimensional strings
     * Convert:
     *  [
     *      'foo'   =>  [
     *          'bar'   =>  'baz
     *      ],
     *      'baa'   =>  'sheep'
     *  ]
     *
     * TO
     *  [
     *      'foo.bar'   =>  'baz',
     *      'baa'       =>  'sheep'
     *  ]
     *
     * @param $arrayContents
     * @return array
     */
    public function arrayKeyToStringPath($arrayContents)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($arrayContents));
        $result = [];
        foreach ($iterator as $leafValue) {
            $keys = [];
            foreach (range(0, $iterator->getDepth()) as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }
            $result[ join(self::SEPARATOR, $keys) ] = $leafValue;
        }
        return $result;
    }

    /**
     * TODO: refactor to not use references
     * @param array $inputArray
     * @return array
     */
    public function stringPathToArrayKey($inputArray = [])
    {
        $tempArray = [];
        foreach ($inputArray as $envVar => $value) {
            $path = explode('.', $envVar);
            $root = &$tempArray;
            while ($branch = array_shift($path)) {
                if ( ! isset($root[$branch])) {
                    $root[$branch] = [];
                }

                $root = &$root[$branch];
            }

            $root = $value;
        }

        return $tempArray;
    }

    /**
     * @param array $inputArray
     * @return array
     */
    public function arrayToSymfonyConsoleTableRows($inputArray = [])
    {
        $rows = [];
        ksort($inputArray);

        foreach ($inputArray as $envVar => $value) {
            $rows[] = [$envVar, $value];
        }

        return $rows;
    }

    /**
     * @param $envVarsInput
     * @param $contents
     * @return mixed
     */
    public function mergeDownArrays($envVarsInput, $contents)
    {
        $envVarsInput += $contents; // merge two arrays

        return $envVarsInput;
    }
}