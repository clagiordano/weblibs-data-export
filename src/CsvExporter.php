<?php

namespace clagiordano\weblibs\dataexport;

/**
 * Class CsvExporter
 * @package clagiordano\weblibs\dataexport
 */
class CsvExporter extends AbstractExporter
{
    /**
     * @param array $fields
     * @param int $mode
     * @param string $fieldSeparator
     * @return bool|int
     */
    public function writeRow(array $fields, $mode = FILE_APPEND, $fieldSeparator = ';')
    {
        $outputString = join($fieldSeparator, $fields) . PHP_EOL;

        return file_put_contents($this->fileName, $outputString, $mode);
    }
}
