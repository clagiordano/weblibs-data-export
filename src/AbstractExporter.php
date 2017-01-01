<?php

namespace clagiordano\weblibs\dataexport;

abstract class AbstractExporter
{
    /** @var string $fileName */
    protected $fileName = null;
    /** @var string $outputMethod */
    protected $outputMethod = null;
    /** @var resource $fileHandle */
    protected $fileHandle = null;
    /** @var string $dataOutput */
    protected $dataOutput = "";
    /** @var bool $writeStatus */
    protected $writeStatus = false;

    /**
     * @constructor
     * @param string $fileName
     * @param string $outputMethod
     *
     * @return \clagiordano\weblibs\dataexport\AbstractExporter
     */
    public function __construct($fileName, $outputMethod = "download")
    {
        if (!OutputMethods::isValid($outputMethod)) {
            throw new \InvalidArgumentException(
                __METHOD__ . ": Invalid output method selected,"
                . " valid method are only: '"
                . implode("', '", OutputMethods::isValid($outputMethod))
                . "'"
            );
        }

        $this->fileName = $fileName;
        $this->outputMethod = $outputMethod;
    }

    /**
     * Method to send http header to force download as attachment
     */
    public function sendHeaders()
    {
        ob_end_clean();
        ini_set('zlib.dataOutput_compression', 'Off');

        header('Pragma: public');
        // Date in the past
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        // HTTP/1.1
        header('Cache-Control: no-store, no-cache, must-revalidate');
        // HTTP/1.1
        header('Cache-Control: pre-check=0, post-check=0, max-age=0');
        header("Pragma: no-cache");
        header("Expires: 0");
        header('Content-Transfer-Encoding: none');
        // This should work for IE & Opera
        header('Content-Type: application/vnd.ms-excel;');
        // This should work for the rest
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="'
            . basename($this->fileName) . '"');
        readfile($this->fileName);
        unlink($this->fileName);
    }

    /**
     * Write a file on disk and, if reuired, download them.
     */
    public function writeFile()
    {
        $this->xlsEOF();

        $this->fileHandle = fopen($this->fileName, 'w+');
        if ($this->fileHandle === false) {
            throw new \RuntimeException(
                "[" . __METHOD__ . "]: Unable to open file '{$this->fileName}'"
            );
        }

        if (fwrite($this->fileHandle, $this->dataOutput)) {
            throw new \RuntimeException(
                "[" . __METHOD__ . "]: Unable to write file '{$this->fileName}'"
            );
        }

        fclose($this->fileHandle);
        $this->writeStatus = true;

        if ($this->outputMethod == "download") {
            $this->sendHeaders();
        }

        return $this->writeStatus;
    }
}
