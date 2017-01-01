<?php

namespace clagiordano\weblibs\dataexport;

class OutputMethods
{
    public static $methods = [
        'save',
        'download'
    ];

    public static function getMethods()
    {
        return self::$methods;
    }

    public static function isValid($method)
    {
        return in_array($method, self::$methods);
    }
}
