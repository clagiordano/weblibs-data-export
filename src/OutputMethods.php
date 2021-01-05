<?php

namespace clagiordano\weblibs\dataexport;

/**
 * Class OutputMethods
 * @package clagiordano\weblibs\dataexport
 */
class OutputMethods
{
    const OUTPUT_SAVE = 'save';
    const OUTPUT_DOWNLOAD = 'download';

    /**
     * Returns allowed output methods
     *
     * @return array
     */
    public static function getMethods()
    {
        return [
            self::OUTPUT_SAVE,
            self::OUTPUT_DOWNLOAD
        ];
    }

    /**
     * Returns if selected output method is isValid
     *
     * @param string $method
     * @return bool
     */
    public static function isValid($method)
    {
        return in_array($method, self::getMethods());
    }
}
