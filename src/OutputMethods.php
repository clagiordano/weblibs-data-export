<?php

namespace clagiordano\weblibs\dataexport;

class OutputMethods
{
    /** @var array $methods */
    public static $methods = [
        'save',
        'download'
    ];

    /**
     * Returns allowed output methods
     *
     * @return array
     */
    public static function getMethods()
    {
        return self::$methods;
    }

    /**
     * Returns if selected output method is isValid
     *
     * @param string $method
     * @return bool
     */
    public static function isValid($method)
    {
        return in_array($method, self::$methods);
    }
}
