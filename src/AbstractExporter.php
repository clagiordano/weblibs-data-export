<?php

namespace clagiordano\weblibs\dataexport;

use \Exception;
use \InvalidArgumentException;
use \RuntimeException;

/**
 * Class AbstractExporter
 * @package clagiordano\weblibs\dataexport
 */
abstract class AbstractExporter
{
    /** @var string $fileName */
    protected $fileName = null;
    /** @var string $outputMethod */
    protected $outputMethod = null;
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
     */
    public function __construct($fileName, $outputMethod = "download")
    {
        $this->fileName = $fileName;
        $this->setOutputMethod(new OutputMethods(), $outputMethod);
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
     * @throw RuntimeException
     * @return bool
     */
    public function writeFile()
    {
        try {
            file_put_contents($this->fileName, $this->dataOutput);
            $this->writeStatus = true;
        } catch (Exception $exc) {
             throw new RuntimeException(
                 __METHOD__ . ": Failed to write file on path '{$this->fileName}'"
             );
        }
        
        if ($this->outputMethod == "download") {
            $this->sendHeaders();
        }

        return $this->writeStatus;
    }

    /**
     * @param OutputMethods $outputs
     * @param string $outputMethod
     */
    protected function setOutputMethod(OutputMethods $outputs, $outputMethod)
    {
        if (!$outputs::isValid($outputMethod)) {
            throw new InvalidArgumentException(
                "Invalid output method selected, valid method are only one of: '"
                . implode("', '", $outputs::getMethods())
                . "'."
            );
        }

        $this->outputMethod = $outputMethod;
    }
}
