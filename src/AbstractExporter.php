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
    /** @var string $contentType */
    protected $contentType = "text/plain";

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
                . " valid method are only one of: '"
                . implode("', '", OutputMethods::getMethods())
                . "'."
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
        header("Content-Type: {$this->contentType};");
        // This should work for the rest
        header("Content-type: {$this->contentType}");
        header('Content-Disposition: attachment; filename="'
            . basename($this->fileName) . '"');
        readfile($this->fileName);
        unlink($this->fileName);
    }

    /**
     * Write a file on disk and, if reuired, send download header and drop file.
     * @throw \RuntimeException
     * @return bool
     */
    public function writeFile()
    {
        try {
            file_put_contents($this->fileName, $this->dataOutput);
            $this->writeStatus = true;
        } catch (\Exception $exc) {
             throw new \RuntimeException(
                __METHOD__ . ": Failed to write file on path '{$this->fileName}'"
            );
        }
        
        if ($this->outputMethod == "download") {
            $this->sendHeaders();
        }

        return $this->writeStatus;
    }
}
