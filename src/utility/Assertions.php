<?php 
declare(strict_types=1);
namespace Example\Utility;

Final Class Assertions
{
    /**
     * Compares whether two values are the same
     */
    public static function assertSame($variable1, $variable2, $errorMessage = "")
    {
        if ($variable1 === $variable2) {
            return true;
        }
        if ($errorMessage == "") {
            $errorMessage = "Failed asserting that " . $variable1 . " is the same as " . $variable2 . PHP_EOL;
        }
        throw new \Exception($errorMessage);
        return false;
    }
    /**
     * Checks whether value is numeric
     */
    public static function assertIsNumeric($value, $errorMessage = "")
    {
        if (is_numeric($value)) {
            return true;
        }
        if ($errorMessage == "") {
            $errorMessage = "Failed asserting that value " . $value . " is numeric" . PHP_EOL;
        }
        throw new \Exception($errorMessage);
        return false;
    }

    /**
     * Checks whether value is integer
     */
    public static function assertIsInt($value, $errorMessage = "")
    {
        if (is_int($value)) {
            return true;
        }
        if ($errorMessage == "") {
            $errorMessage = "Failed asserting that value " . $value . " is integer" . PHP_EOL;
        }
        throw new \Exception($errorMessage);
        return false;
    }
    /**
     * Checks whether value is string
     */
    public static function assertIsString($value, $errorMessage = "")
    {
        if (is_string($value)) {
            return true;
        }
        if ($errorMessage == "") {
            $errorMessage = "Failed asserting that value " . $value . " is string" . PHP_EOL;
        }
        throw new \Exception($errorMessage);
        return false;
    }

    /**
     * Checks whether value is not empty
     */
    public static function assertNotEmpty($value, $errorMessage = "")
    {
        if (!empty($value)) {
            return true;
        }
        if ($errorMessage == "") {
            $errorMessage = "Failed asserting that value " . var_dump($value) . " is not empty" . PHP_EOL;
        }
        throw new \Exception($errorMessage);
        return false;
    }

    /**
     * Checks whether value is true
     */
    public static function assertTrue($value, $errorMessage = "")
    {
        if ($value) {
            return true;
        }
        if ($errorMessage == "") {
            $errorMessage = "Failed asserting that value " . var_dump($value) . " is true" . PHP_EOL;
        }
        throw new \Exception($errorMessage);
        return false;
    }

     /**
     * Compares whether two values are the equals
     */
    public static function assertEquals($variable1, $variable2, $errorMessage = "")
    {
        if ($variable1 == $variable2) {
            return true;
        }
        if ($errorMessage == "") {
            $errorMessage = "Failed asserting that " . $variable1 . " is equal to " . $variable2 . PHP_EOL;
        }
        throw new \Exception($errorMessage);
        return false;
    }

    /**
     * Checks whether value is array
     */
    public static function assertIsArray($value, $errorMessage = "")
    {
        if (is_array($value)) {
            return true;
        }
        if ($errorMessage == "") {
            $errorMessage = "Failed asserting that value " . $value . " is array" . PHP_EOL;
        }
        throw new \Exception($errorMessage);
        return false;
    }
}