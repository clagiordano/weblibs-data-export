<?php

namespace clagiordano\weblibs\dataexport;

/**
 * Class to export data to xls format and write a file and/or download them.
 * @package data-export
 */
class XlsExporter extends AbstractExporter
{
    /** @var string $contentType override with specific content type for download */
    protected $contentType = "application/vnd.ms-excel";

    /**
     * XlsExporter constructor.
     * @param string $fileName
     * @param string $dataOutputMethod
     */
    public function __construct($fileName, $dataOutputMethod = "download")
    {
        parent::__construct($fileName, $dataOutputMethod);

        $this->bof();
    }

    /**
     * Create header of xls file (begin of file)
     *
     * @return XlsExporter
     */
    private function bof()
    {
        $this->dataOutput = pack('ssssss', 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);

        return $this;
    }

    /**
     * Write a cell formatted to number at coord $row, $col
     *
     * @param int $row Row number of the file
     * @param int $col Column number of the file
     * @param int $value Cell content value
     *
     * @return XlsExporter
     */
    public function writeNumber($row, $col, $value)
    {
        $this->dataOutput .= pack('sssss', 0x203, 14, $row, $col, 0x0);
        $this->dataOutput .= pack('d', $value);

        return $this;
    }

    /**
     * Write a cell formatted to string at coord $row, $col
     *
     * @param int $row Row number of the file
     * @param int $col Column number of the file
     * @param string $value Cell content value
     *
     * @return XlsExporter
     */
    public function writeString($row, $col, $value)
    {
        $lenght = strlen($value);
        $this->dataOutput .= pack('ssssss', 0x204, 8 + $lenght, $row, $col, 0x0, $lenght);
        $this->dataOutput .= $value;

        return $this;
    }

    /**
     * Create the footer of xls file (end of file)
     *
     * @return XlsExporter
     */
    private function eof()
    {
        $this->dataOutput .= pack('ss', 0x0A, 0x00);

        return $this;
    }

    /**
     * Write a file on disk and, if required, download them.
     */
    public function writeFile()
    {
        $this->eof();

        return parent::writeFile();
    }
}
