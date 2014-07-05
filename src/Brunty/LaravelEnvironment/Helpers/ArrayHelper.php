<?php namespace Brunty\LaravelEnvironment\Helpers;

class ArrayHelper {

    protected $arrayKeyParents = [];
    protected $envVarsTempArray = [];
    const KEY_SEPARATOR = '.';

    /**
     * Returns our rule key for an input value.
     *
     * For example:
     *
     * If we passed in $arr['input']['personal']['name'] to arrayKeyToStringPath()
     *
     * in $this->arrayKeyParents we'd have:
     *
     * [0]        =>        'input',
     * [1]        =>        'personal'
     *
     * and our $key would be $name.
     *
     * We can then use this array of parent items to re-construct it as a string path
     *
     * @param string $key [optional]     Key of the final item to go on there...
     * @return  string  the rule key of the rule based on parents
     */
    protected function generatePathKey($key = '')
    {
        // set the rule key to return
        $ruleKey = '';

        // if we have parents - implode using the separator
        if(count($this->arrayKeyParents) > 0)
        {
            $ruleKey = implode(self::KEY_SEPARATOR, $this->arrayKeyParents) . self::KEY_SEPARATOR;
        }

        // append the key of the value onto the end
        $ruleKey .= $key;

        // return it
        return $ruleKey;
    }

    public function arrayKeyToStringPath($contents)
    {
        foreach($contents as $key => $value)
        {
            if(is_array($value))
            {
                $this->arrayKeyParents[] = $key;
                $this->arrayKeyToStringPath($value); // iterate!
            }
            else
            {
                $ruleKey = $this->generatePathKey($key);
                $this->envVarsTempArray[$ruleKey] = $value;
            }
        }

        $this->arrayKeyParents = []; // use this var to hold any parent elements of the current item we're on - reset now we're past the iteration

        return $this->envVarsTempArray;
    }

    public function stringPathToArrayKey($input = [])
    {
        $tempArray = [];
        foreach($input as $envVar => $value) {
            $path = explode('.', $envVar);
            $root = &$tempArray;
            while(count($path) > 1) {
                $branch = array_shift($path);
                if (!isset($root[$branch])) {
                    $root[$branch] = array();
                }

                $root = &$root[$branch];
            }

            $root[$path[0]] = $value;
        }

        return $tempArray;
    }
}